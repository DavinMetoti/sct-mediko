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
            'quiz_mode' => 'required|in:new'
        ]);

        $category = MedmasteryCategory::findOrFail($categoryId);
        $questionCount = $request->question_count;
        $user = Auth::user();
        
        // Create a unique session key for this quiz
        $sessionKey = 'quiz_session_' . $categoryId . '_' . ($user ? $user->id : 'guest');
        
        // Clear any existing session for new quiz
        if (session()->has($sessionKey)) {
            session()->forget($sessionKey);
            Log::info('Cleared existing session for new quiz', ['session_key' => $sessionKey]);
        }
        
        // Create new questions set
        $questions = MedMasteryQuestion::active()
            ->byCategory($categoryId)
            ->inRandomOrder()
            ->limit($questionCount)
            ->get();
            
        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada soal tersedia untuk kategori ini.');
        }
        
        // Store question IDs in session
        $questionIds = $questions->pluck('id')->toArray();
        session([
            $sessionKey => [
                'category_id' => $categoryId,
                'question_count' => $questionCount,
                'total_questions' => $questionCount,
                'question_ids' => $questionIds,
                'quiz_mode' => 'new',
                'created_at' => now()->timestamp
            ]
        ]);
        
        Log::info('Created new quiz session', [
            'session_key' => $sessionKey,
            'question_ids' => $questionIds,
            'mode' => 'new'
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
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $answers = $request->answers ?? [];
        $selfAssessments = $request->self_assessment ?? [];
        $totalQuestions = count($answers);
        
        // Create main answer record
        $medMasteryAnswer = MedMasteryAnswer::create([
            'user_id' => $user->id,
            'med_mastery_category_id' => $categoryId,
            'total_questions' => $totalQuestions,
            'answer' => json_encode([
                'answers' => $answers,
                'self_assessments' => $selfAssessments
            ])
        ]);

        // Create answer details for each question and calculate scores
        $totalScore = 0;
        
        foreach ($answers as $questionId => $answerText) {
            $selfAssessment = $selfAssessments[$questionId] ?? null;
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
        }
        
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

        return redirect()->route('medmastery.quiz.result', ['categoryId' => $categoryId, 'answerId' => $medMasteryAnswer->id])
            ->with('success', 'Quiz berhasil diselesaikan!');
    }
    
    /**
     * Calculate score for individual question based on self-assessment
     */
    private function calculateQuestionScore($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return 1.0;     // Full score
            case 'hampir_benar':
                return 0.5;     // Half score
            case 'salah':
            default:
                return 0.0;     // No score
        }
    }
    
    /**
     * Evaluate answer based on self-assessment
     */
    private function evaluateAnswer($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return true;
            case 'hampir_benar':
                return true; // Consider as partially correct
            case 'salah':
            default:
                return false;
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
