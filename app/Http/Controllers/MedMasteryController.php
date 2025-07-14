<?php

namespace App\Http\Controllers;

use App\Models\MedmasteryCategory;
use App\Models\MedMasteryQuestion;
use App\Models\MedMasteryAnswer;
use App\Models\MedMasteryAnswerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MedMasteryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MedmasteryCategory::with(['segmentation', 'creator'])
            ->withCount(['questions', 'activeQuestions'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get user's quiz count if authenticated
        $userQuizCount = 0;
        if (Auth::check()) {
            $userQuizCount = MedMasteryAnswer::where('user_id', Auth::id())->count();
        }
            
        return view('medmastery.content.dashboard', compact('categories', 'userQuizCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Show category detail for students
     */
    public function showCategory(string $id)
    {
        $category = MedmasteryCategory::with(['segmentation', 'creator'])
            ->withCount(['questions', 'activeQuestions'])
            ->findOrFail($id);
        
        $user = Auth::user();
        $userRole = $user ? $user->accessRole : null;
        
        return view('medmastery.content.category-show', compact('category', 'user', 'userRole'));
    }

    /**
     * Start quiz with selected number of questions
     */
    public function startQuiz(Request $request, string $categoryId)
    {
        // Debug: log request details
        Log::info('Quiz start request', [
            'categoryId' => $categoryId,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $request->validate([
            'question_count' => 'required|numeric|min:1|max:50',
            'quiz_mode' => 'required|in:new,wrong'
        ]);

        $category = MedmasteryCategory::findOrFail($categoryId);
        $questionCount = $request->question_count;
        $quizMode = $request->quiz_mode;
        $user = Auth::user();
        
        // Create a unique session key for this quiz
        $sessionKey = 'quiz_session_' . $categoryId . '_' . ($user ? $user->id : 'guest');
        
        // Clear any existing session for new quiz
        if (session()->has($sessionKey)) {
            session()->forget($sessionKey);
            Log::info('Cleared existing session for new quiz', ['session_key' => $sessionKey]);
        }
        
        // Get questions based on quiz mode
        if ($quizMode === 'wrong' && $user) {
            // Get wrong question IDs for this user
            $wrongQuestionIds = $this->getWrongQuestionIds($categoryId, $user->id);
            
            if (empty($wrongQuestionIds)) {
                return redirect()->back()->with('error', 'Tidak ada soal yang salah untuk dikerjakan ulang.');
            }
            
            // Get questions from wrong IDs
            $questions = MedMasteryQuestion::active()
                ->whereIn('id', $wrongQuestionIds)
                ->inRandomOrder()
                ->limit($questionCount)
                ->get();
        } else {
            // Create new questions set (original logic)
            $questions = MedMasteryQuestion::active()
                ->byCategory($categoryId)
                ->inRandomOrder()
                ->limit($questionCount)
                ->get();
        }
            
        if ($questions->isEmpty()) {
            $errorMessage = $quizMode === 'wrong' 
                ? 'Tidak ada soal yang salah tersedia untuk kategori ini.'
                : 'Tidak ada soal tersedia untuk kategori ini.';
            return redirect()->back()->with('error', $errorMessage);
        }
        
        // Store question IDs in session
        $questionIds = $questions->pluck('id')->toArray();
        session([
            $sessionKey => [
                'category_id' => $categoryId,
                'question_count' => $questionCount,
                'total_questions' => $questionCount,
                'question_ids' => $questionIds,
                'quiz_mode' => $quizMode,
                'created_at' => now()->timestamp
            ]
        ]);
        
        Log::info('Created new quiz session', [
            'session_key' => $sessionKey,
            'question_ids' => $questionIds,
            'mode' => $quizMode
        ]);

        $userRole = $user ? $user->accessRole : null;
        
        return view('medmastery.content.quiz', compact('category', 'questions', 'user', 'userRole'));
    }

    /**
     * Submit quiz answers
     */
    public function submitQuiz(Request $request, string $categoryId)
    {
        $category = MedmasteryCategory::findOrFail($categoryId);
        $user = Auth::user();
        
        if (!$user) {
            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu.',
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $answers = $request->answers ?? [];
        $selfAssessments = $request->self_assessment ?? [];
        
        // Debug: Log the incoming data
        Log::info('Quiz submission data', [
            'user_id' => $user->id,
            'category_id' => $categoryId,
            'answers_count' => count($answers),
            'answers' => $answers,
            'self_assessments_count' => count($selfAssessments),
            'self_assessments' => $selfAssessments,
            'all_request_data' => $request->all()
        ]);
        
        // Filter out empty answers
        $validAnswers = array_filter($answers, function($answer) {
            return !empty(trim($answer));
        });
        
        $totalQuestions = count($validAnswers);
        
        // Check if there are any valid answers
        if ($totalQuestions === 0) {
            Log::warning('No valid answers found in quiz submission', [
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'raw_answers' => $answers
            ]);
            
            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan jawab minimal 1 pertanyaan sebelum submit.'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Silakan jawab minimal 1 pertanyaan sebelum submit.');
        }
        
        // Create main answer record
        $medMasteryAnswer = MedMasteryAnswer::create([
            'user_id' => $user->id,
            'med_mastery_category_id' => $categoryId,
            'total_questions' => $totalQuestions,
            'answer' => json_encode([
                'answers' => $validAnswers,
                'self_assessments' => $selfAssessments
            ])
        ]);

        // Create answer details for each question and calculate scores
        $totalScore = 0;
        
        foreach ($validAnswers as $questionId => $answerText) {
            $selfAssessment = $selfAssessments[$questionId] ?? null;
            
            // If no self-assessment provided but user answered, assume it's correct
            if (empty($selfAssessment) && !empty(trim($answerText))) {
                $selfAssessment = 'benar'; // Default to correct for answered questions
                Log::info('Applied default self-assessment', [
                    'question_id' => $questionId,
                    'original_assessment' => $selfAssessments[$questionId] ?? 'NULL',
                    'applied_assessment' => $selfAssessment
                ]);
            }
            
            $questionScore = $this->calculateQuestionScore($selfAssessment);
            $totalScore += $questionScore;
            
            MedMasteryAnswerDetail::create([
                'med_mastery_question_id' => $questionId,
                'med_mastery_answer_id' => $medMasteryAnswer->id,
                'answer_text' => $answerText,
                'self_assessment' => $selfAssessment,
                'score' => $questionScore,
                'is_correct' => $this->evaluateAnswer($selfAssessment)
            ]);
            
            Log::info('Created answer detail', [
                'question_id' => $questionId,
                'answer_text' => substr($answerText, 0, 50) . '...',
                'self_assessment' => $selfAssessment ?? 'NULL',
                'calculated_score' => $questionScore,
                'is_correct' => $this->evaluateAnswer($selfAssessment)
            ]);
        }
        
        Log::info('Score calculation summary', [
            'total_score' => $totalScore,
            'total_questions' => $totalQuestions,
            'self_assessments_received' => $selfAssessments,
            'self_assessments_count' => count($selfAssessments)
        ]);
        
        // Calculate final score: (total score / total questions) * 100, max 100
        $finalScore = $totalQuestions > 0 ? min(100, ($totalScore / $totalQuestions) * 100) : 0;
        
        // Update the main answer record with the calculated score
        $medMasteryAnswer->update([
            'score' => round($finalScore, 2)
        ]);
        
        // Clear the quiz session since it's completed
        $sessionKey = 'quiz_session_' . $categoryId . '_' . $user->id;
        session()->forget($sessionKey);
        
        Log::info('Quiz completed and session cleared', [
            'session_key' => $sessionKey,
            'answer_id' => $medMasteryAnswer->id,
            'final_score' => $finalScore
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil diselesaikan!',
                'redirect' => route('medmastery.quiz.result', ['categoryId' => $categoryId, 'answerId' => $medMasteryAnswer->id]),
                'data' => [
                    'answer_id' => $medMasteryAnswer->id,
                    'final_score' => $finalScore,
                    'total_questions' => $totalQuestions
                ]
            ]);
        }

        return redirect()->route('medmastery.quiz.result', ['categoryId' => $categoryId, 'answerId' => $medMasteryAnswer->id])
            ->with('success', 'Quiz berhasil diselesaikan!');
    }
    
    /**
     * Calculate score for individual question based on self-assessment
     * If no self-assessment is provided but answer exists, give partial credit
     */
    private function calculateQuestionScore($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return 1.0;     // Full score
            case 'hampir_benar':
                return 0.5;     // Half score
            case 'salah':
                return 0.0;     // No score
            case null:
            case '':
            default:
                // If no self-assessment provided but answer exists, give default partial credit
                return 0.7;     // Default score for answered but not self-assessed questions
        }
    }
    
    /**
     * Evaluate answer based on self-assessment
     * If no self-assessment is provided but answer exists, consider as correct
     */
    private function evaluateAnswer($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return true;
            case 'hampir_benar':
                return true; // Consider as partially correct
            case 'salah':
                return false;
            case null:
            case '':
            default:
                // If no self-assessment provided but answer exists, consider as correct
                return true;
        }
    }

    /**
     * Show quiz results
     */
    public function showQuizResult(string $categoryId, string $answerId)
    {
        $category = MedmasteryCategory::findOrFail($categoryId);
        $answer = MedMasteryAnswer::with(['answerDetails.question'])
            ->where('id', $answerId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $user = Auth::user();
        $userRole = $user ? $user->accessRole : null;
        
        return view('medmastery.content.quiz-result', compact('category', 'answer', 'user', 'userRole'));
    }

    /**
     * Restart quiz - clear session and start fresh
     */
    public function restartQuiz(Request $request, string $categoryId)
    {
        $user = Auth::user();
        $sessionKey = 'quiz_session_' . $categoryId . '_' . ($user ? $user->id : 'guest');
        
        // Clear existing session
        session()->forget($sessionKey);
        
        Log::info('Quiz session restarted', [
            'session_key' => $sessionKey,
            'category_id' => $categoryId
        ]);
        
        // Redirect back to category page to start fresh
        return redirect()->route('medmastery.category.show', $categoryId)
            ->with('success', 'Quiz session berhasil direset. Silakan pilih jumlah soal untuk memulai quiz baru.');
    }

    /**
     * Show user's quiz history/results
     */
    public function userHistory()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user's quiz results with category information
        $quizResults = MedMasteryAnswer::with(['category.segmentation', 'answerDetails.question'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $userRole = $user->accessRole;
        
        return view('medmastery.content.user-history', compact('quizResults', 'user', 'userRole'));
    }

    /**
     * Show detailed quiz result for user's history
     */
    public function userHistoryDetail(string $answerId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get specific quiz result for this user
        $answer = MedMasteryAnswer::with(['category.segmentation', 'answerDetails.question'])
            ->where('id', $answerId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $category = $answer->category;
        $userRole = $user->accessRole;
        
        return view('medmastery.content.quiz-result', compact('category', 'answer', 'user', 'userRole'));
    }

    /**
     * Get count of wrong questions for a user in a specific category
     */
    public function getWrongQuestionsCount(string $categoryId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['count' => 0, 'message' => 'User not authenticated']);
        }

        // Debug: Log the request
        Log::info('Getting wrong questions count', [
            'user_id' => $user->id,
            'category_id' => $categoryId
        ]);

        // Get all answer details for this user and category where score < 1
        // Use distinct to avoid counting same questions multiple times
        $wrongQuestionsCount = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
            ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
            ->where('med_mastery_answers.user_id', $user->id)
            ->where('med_mastery_answers.med_mastery_category_id', $categoryId)
            ->where('med_mastery_answer_details.score', '<', 1) // Score kurang dari 1 (salah)
            ->where('med_mastery_questions.is_active', true) // Only active questions
            ->distinct()
            ->count('med_mastery_answer_details.med_mastery_question_id');

        // Debug: Log the result
        Log::info('Wrong questions count result', [
            'count' => $wrongQuestionsCount,
            'user_id' => $user->id,
            'category_id' => $categoryId
        ]);

        return response()->json(['count' => $wrongQuestionsCount]);
    }

    /**
     * Get wrong question IDs for a user in a specific category
     */
    private function getWrongQuestionIds(string $categoryId, int $userId)
    {
        // Debug: Log the request
        Log::info('Getting wrong question IDs', [
            'user_id' => $userId,
            'category_id' => $categoryId
        ]);

        $wrongQuestionIds = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
            ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
            ->where('med_mastery_answers.user_id', $userId)
            ->where('med_mastery_answers.med_mastery_category_id', $categoryId)
            ->where('med_mastery_answer_details.score', '<', 1) // Score kurang dari 1 (salah)
            ->where('med_mastery_questions.is_active', true) // Only active questions
            ->distinct()
            ->pluck('med_mastery_answer_details.med_mastery_question_id')
            ->toArray();

        // Debug: Log the result
        Log::info('Wrong question IDs result', [
            'question_ids' => $wrongQuestionIds,
            'count' => count($wrongQuestionIds),
            'user_id' => $userId,
            'category_id' => $categoryId
        ]);

        return $wrongQuestionIds;
    }

    /**
     * Debug method to test wrong questions query (temporary - can be removed later)
     */
    public function debugWrongQuestions(string $categoryId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated']);
        }

        // Get all answer details for this user and category
        $allAnswers = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
            ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
            ->where('med_mastery_answers.user_id', $user->id)
            ->where('med_mastery_answers.med_mastery_category_id', $categoryId)
            ->select(
                'med_mastery_answer_details.med_mastery_question_id',
                'med_mastery_answer_details.score',
                'med_mastery_answer_details.self_assessment',
                'med_mastery_questions.is_active'
            )
            ->get();

        // Get wrong answers (score < 1)
        $wrongAnswers = $allAnswers->where('score', '<', 1);
        
        // Get active wrong answers
        $activeWrongAnswers = $wrongAnswers->where('is_active', true);
        
        // Get unique question IDs
        $uniqueWrongQuestionIds = $activeWrongAnswers->pluck('med_mastery_question_id')->unique();

        return response()->json([
            'user_id' => $user->id,
            'category_id' => $categoryId,
            'total_answers' => $allAnswers->count(),
            'wrong_answers' => $wrongAnswers->count(),
            'active_wrong_answers' => $activeWrongAnswers->count(),
            'unique_wrong_questions' => $uniqueWrongQuestionIds->count(),
            'all_answers_sample' => $allAnswers->take(5),
            'wrong_answers_sample' => $wrongAnswers->take(5),
            'unique_question_ids' => $uniqueWrongQuestionIds->values()->all()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
