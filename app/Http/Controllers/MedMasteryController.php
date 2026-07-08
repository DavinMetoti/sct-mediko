<?php

namespace App\Http\Controllers;

use App\Models\MedmasteryCategory;
use App\Models\MedMasteryQuestion;
use App\Models\MedMasteryAnswer;
use App\Models\MedMasteryAnswerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MedMasteryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Optimized query with selective fields and efficient joins
        $categories = MedmasteryCategory::select([
            'medmastery_category.id',
            'medmastery_category.name',
            'medmastery_category.description',
            'medmastery_category.access',
            'medmastery_category.icon',
            'medmastery_category.created_at',
            'medmastery_category.created_by',
            'medmastery_category.medmastery_segmentation_id'
        ])
        ->with([
            'segmentation:id,name,color',
            'creator:id,name'
        ])
        ->withCount(['questions', 'activeQuestions'])
        ->where(function($query) use ($user) {
            $query->where('access', 'public');
            
            // If user is logged in, also show their private categories
            if ($user) {
                $query->orWhere(function($q) use ($user) {
                    $q->where('access', 'private')
                      ->where('created_by', $user->id);
                });
            }
        })
        ->whereHas('segmentation', function($query) use ($user) {
            // If no user is logged in, only show segmentations with no access restrictions
            if (!$user) {
                $query->whereDoesntHave('allowedUsers');
            } else {
                // For logged in users, show segmentations with no restrictions OR where user is specifically allowed
                $query->where(function($q) use ($user) {
                    $q->whereDoesntHave('allowedUsers') // No restrictions - all users can access
                      ->orWhereHas('allowedUsers', function($allowedQuery) use ($user) {
                          $allowedQuery->where('users.id', $user->id); // User is specifically allowed
                      });
                });
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();
            
        // Get user's quiz count if authenticated (cached for 5 minutes)
        $userQuizCount = 0;
        if ($user) {
            $cacheKey = 'user_quiz_count_' . $user->id;
            $userQuizCount = Cache::remember($cacheKey, 300, function () use ($user) {
                return MedMasteryAnswer::where('user_id', $user->id)->count();
            });
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
            ->findOrFail($id);
        
        $user = Auth::user();
        
        // Check segmentation access
        if ($category->segmentation) {
            $hasAccess = false;
            
            if ($category->segmentation->allowedUsers->isEmpty()) {
                // No restrictions - all users can access
                $hasAccess = true;
            } elseif ($user && $category->segmentation->allowedUsers->contains($user->id)) {
                // User is specifically allowed
                $hasAccess = true;
            }
            
            if (!$hasAccess) {
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Silakan login untuk mengakses kategori ini.');
                } else {
                    abort(403, 'Anda tidak memiliki akses ke kategori ini.');
                }
            }
        }
        
        $userRole = $user ? $user->accessRole : null;
        
        // Get accessible questions count based on visibility
        $accessibleQuestionsCount = MedMasteryQuestion::where('medmastery_category_id', $id);
        
        if ($user) {
            // For logged in users: own questions OR public questions
            $accessibleQuestionsCount->where(function($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhere('is_public', true);
            });
        } else {
            // For guests: only public questions
            $accessibleQuestionsCount->where('is_public', true);
        }
        
        $category->accessible_questions_count = $accessibleQuestionsCount->count();
        $category->accessible_active_questions_count = $accessibleQuestionsCount->where('is_active', true)->count();
        
        // Get unanswered questions count for logged in users
        if ($user) {
            $unansweredQuestionsCount = MedMasteryQuestion::active()
                ->byCategory($id)
                ->where(function($q) use ($user) {
                    $q->where('creator_id', $user->id)
                      ->orWhere('is_public', true);
                })
                ->notAnsweredByUser($user->id)
                ->count();
            $category->unanswered_questions_count = $unansweredQuestionsCount;
            
            // Get wrong questions count (questions where max score < 1)
            $wrongQuestionsCount = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
                ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
                ->where('med_mastery_answers.user_id', $user->id)
                ->where('med_mastery_answers.med_mastery_category_id', $id)
                ->where('med_mastery_questions.is_active', true) // Only active questions
                ->where(function($query) use ($user) {
                    // Only include questions that user can access (own questions OR public questions)
                    $query->where('med_mastery_questions.creator_id', $user->id)
                          ->orWhere('med_mastery_questions.is_public', true);
                })
                ->selectRaw('med_mastery_answer_details.med_mastery_question_id, MAX(med_mastery_answer_details.score) as max_score')
                ->groupBy('med_mastery_answer_details.med_mastery_question_id')
                ->having('max_score', '<', 1)
                ->count();
            $category->wrong_questions_count = $wrongQuestionsCount;
            
            Log::info('Category counts', [
                'category_id' => $id,
                'user_id' => $user->id,
                'unanswered_count' => $unansweredQuestionsCount,
                'wrong_count' => $category->wrong_questions_count,
                'accessible_active' => $category->accessible_active_questions_count
            ]);
        } else {
            // For guests, all accessible questions are unanswered
            $category->unanswered_questions_count = $category->accessible_active_questions_count;
            $category->wrong_questions_count = 0;
        }
        
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
            'quiz_mode' => 'required|in:new,wrong,review'
        ]);

        $category = MedmasteryCategory::findOrFail($categoryId);
        
        // Check segmentation access
        $user = Auth::user();
        if ($category->segmentation) {
            $hasAccess = false;
            
            if ($category->segmentation->allowedUsers->isEmpty()) {
                // No restrictions - all users can access
                $hasAccess = true;
            } elseif ($user && $category->segmentation->allowedUsers->contains($user->id)) {
                // User is specifically allowed
                $hasAccess = true;
            }
            
            if (!$hasAccess) {
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Silakan login untuk mengakses kategori ini.');
                } else {
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kategori ini.');
                }
            }
        }
        
        $questionCount = $request->question_count;
        $quizMode = $request->quiz_mode;
        
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
            
            // Get questions from wrong IDs with proper visibility filtering
            $questionsQuery = MedMasteryQuestion::active()
                ->whereIn('id', $wrongQuestionIds);
            
            // Apply visibility filter: only show user's own questions OR public questions
            if ($user) {
                $questionsQuery->where(function($q) use ($user) {
                    $q->where('creator_id', $user->id)  // User's own questions
                      ->orWhere('is_public', true);      // OR public questions
                });
            } else {
                // For guests, only show public questions
                $questionsQuery->where('is_public', true);
            }
            
            $questions = $questionsQuery->inRandomOrder()
                ->limit($questionCount)
                ->get();
        } elseif ($quizMode === 'review' && $user) {
            // Get correct question IDs for this user (questions answered correctly)
            $correctQuestionIds = $this->getCorrectQuestionIds($categoryId, $user->id);
            
            if (empty($correctQuestionIds)) {
                return redirect()->back()->with('error', 'Tidak ada soal yang sudah dikerjakan dengan benar untuk di-review.');
            }
            
            // Get questions from correct IDs with proper visibility filtering
            $questionsQuery = MedMasteryQuestion::active()
                ->whereIn('id', $correctQuestionIds);
            
            // Apply visibility filter: only show user's own questions OR public questions
            $questionsQuery->where(function($q) use ($user) {
                $q->where('creator_id', $user->id)  // User's own questions
                  ->orWhere('is_public', true);      // OR public questions
            });
            
            $questions = $questionsQuery->inRandomOrder()
                ->limit($questionCount)
                ->get();
        } else {
            // Create new questions set with proper visibility filtering
            $questionsQuery = MedMasteryQuestion::active()
                ->byCategory($categoryId);
            
            // Apply visibility filter: only show user's own questions OR public questions
            if ($user) {
                $questionsQuery->where(function($q) use ($user) {
                    $q->where('creator_id', $user->id)  // User's own questions
                      ->orWhere('is_public', true);      // OR public questions
                });
            } else {
                // For guests, only show public questions
                $questionsQuery->where('is_public', true);
            }
            
            // Get total questions count
            $totalQuestions = $questionsQuery->count();
            
            if ($totalQuestions == 0) {
                return redirect()->back()->with('error', 'Tidak ada soal tersedia untuk kategori ini.');
            }
            
            // Get count of answers by this user in this category
            $answeredCount = 0;
            if ($user) {
                $answeredCount = MedMasteryAnswerDetail::whereHas('answer', function($q) use ($user, $categoryId) {
                    $q->where('user_id', $user->id);
                })->whereHas('question', function($q) use ($categoryId) {
                    $q->where('medmastery_category_id', $categoryId);
                })->count();
            }
            
            // Calculate offset for sequential selection
            $offset = $answeredCount % $totalQuestions;
            
            // Get questions sequentially starting from offset, wrapping around if needed
            $questions = collect();
            $remaining = $questionCount;
            $currentOffset = $offset;
            
            while ($remaining > 0) {
                $take = min($remaining, $totalQuestions - $currentOffset);
                $batch = $questionsQuery->orderBy('id')->skip($currentOffset)->take($take)->get();
                $questions = $questions->merge($batch);
                $remaining -= $take;
                $currentOffset = 0; // Next batch from beginning
            }
        }
            
        if ($questions->isEmpty()) {
            $errorMessage = '';
            if ($quizMode === 'wrong') {
                $errorMessage = 'Tidak ada soal yang salah tersedia untuk kategori ini.';
            } elseif ($quizMode === 'review') {
                $errorMessage = 'Tidak ada soal yang sudah dikerjakan dengan benar untuk di-review.';
            } else {
                $errorMessage = 'Tidak ada soal tersedia untuk kategori ini.';
            }
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
        
        // Check segmentation access
        $user = Auth::user();
        if ($category->segmentation) {
            $hasAccess = false;
            
            if ($category->segmentation->allowedUsers->isEmpty()) {
                // No restrictions - all users can access
                $hasAccess = true;
            } elseif ($user && $category->segmentation->allowedUsers->contains($user->id)) {
                // User is specifically allowed
                $hasAccess = true;
            }
            
            if (!$hasAccess) {
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Silakan login untuk mengakses kategori ini.');
                } else {
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kategori ini.');
                }
            }
        }
        
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
        
        // Get the list of all question IDs in this quiz from the session or request
        $sessionKey = 'quiz_session_' . $categoryId . '_' . $user->id;
        $sessionData = session($sessionKey);
        $questionIds = $sessionData['question_ids'] ?? [];
        
        if (empty($questionIds)) {
            // Fallback: merge keys from answers and self-assessments
            $questionIds = array_unique(array_merge(array_keys($answers), array_keys($selfAssessments)));
        }
        
        $totalQuestions = count($questionIds);
        
        // Filter out empty answers for the json_encode storage block
        $validAnswers = array_filter($answers, function($answer) {
            return !empty(trim($answer));
        });
        
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
        
        foreach ($questionIds as $questionId) {
            $answerText = $answers[$questionId] ?? '';
            $selfAssessment = $selfAssessments[$questionId] ?? null;
            
            // Log the self-assessment that was actually received
            Log::info('Processing question assessment', [
                'question_id' => $questionId,
                'received_assessment' => $selfAssessment,
                'answer_text_preview' => substr($answerText, 0, 50) . '...'
            ]);
            
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
     * If no self-assessment is provided, require user to assess before submitting
     */
    private function calculateQuestionScore($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return 1.0;     // Full score (100%)
            case 'hampir_benar':
                return 0.5;     // Half score (50%)
            case 'salah':
                return 0.0;     // No score (0%)
            case null:
            case '':
            default:
                // If no self-assessment provided, assign minimal score to encourage proper assessment
                return 0.0;     // No score for unevaluated answers
        }
    }
    
    /**
     * Evaluate answer based on self-assessment
     * If no self-assessment is provided, consider as incorrect to encourage proper assessment
     */
    private function evaluateAnswer($selfAssessment)
    {
        switch ($selfAssessment) {
            case 'benar':
                return true;    // Fully correct
            case 'hampir_benar':
                return true;    // Partially correct (but still considered as "correct" in boolean evaluation)
            case 'salah':
                return false;   // Incorrect
            case null:
            case '':
            default:
                // If no self-assessment provided, consider as incorrect to encourage assessment
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
    public function userHistory(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user's quiz results with category information
        $query = MedMasteryAnswer::with(['category.segmentation', 'answerDetails.question'])
            ->where('user_id', $user->id);
            
        // Apply category filter if provided
        if ($request->has('category') && !empty($request->category)) {
            $query->where('med_mastery_category_id', $request->category);
        }
            
        $quizResults = $query->orderBy('created_at', 'desc')
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
            ->where('user_id', $user->id)
            ->findOrFail($answerId);

        $category = $answer->category;
        $userRole = $user->accessRole;
        
        return view('medmastery.content.quiz-result', compact('category', 'answer', 'user', 'userRole'));
    }

    /**
     * Show user's performance analytics across categories
     */
    public function userPerformance()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get all quiz results for this user with category information
        $quizResults = MedMasteryAnswer::with(['category.segmentation', 'answerDetails.question'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate performance by category
        $categoryPerformance = $quizResults->groupBy('med_mastery_category_id')->map(function ($categoryQuizzes) {
            $category = $categoryQuizzes->first()->category;
            $totalQuizzes = $categoryQuizzes->count();
            $avgScore = $categoryQuizzes->avg('score');
            $bestScore = $categoryQuizzes->max('score');
            $worstScore = $categoryQuizzes->min('score');
            $latestQuiz = $categoryQuizzes->sortByDesc('created_at')->first();
            $totalQuestions = $categoryQuizzes->sum('total_questions');
            $totalAnswered = $categoryQuizzes->sum(function ($quiz) {
                return $quiz->answerDetails->count();
            });
            
            // Calculate improvement trend (last 3 quizzes vs first 3 quizzes)
            $recentQuizzes = $categoryQuizzes->sortByDesc('created_at')->take(3);
            $oldQuizzes = $categoryQuizzes->sortBy('created_at')->take(3);
            $recentAvg = $recentQuizzes->avg('score') ?? 0;
            $oldAvg = $oldQuizzes->avg('score') ?? 0;
            $improvementTrend = $recentAvg - $oldAvg;
            
            return [
                'category' => $category,
                'total_quizzes' => $totalQuizzes,
                'avg_score' => round($avgScore, 1),
                'best_score' => round($bestScore, 1),
                'worst_score' => round($worstScore, 1),
                'latest_quiz' => $latestQuiz,
                'total_questions' => $totalQuestions,
                'total_answered' => $totalAnswered,
                'completion_rate' => $totalQuestions > 0 ? round(($totalAnswered / $totalQuestions) * 100, 1) : 0,
                'improvement_trend' => round($improvementTrend, 1),
                'score_range' => round($bestScore - $worstScore, 1)
            ];
        })->sortByDesc('avg_score');

        // Overall statistics
        $overallStats = [
            'total_quizzes' => $quizResults->count(),
            'total_categories' => $categoryPerformance->count(),
            'overall_avg_score' => round($quizResults->avg('score'), 1),
            'best_category' => $categoryPerformance->first(),
            'most_active_category' => $categoryPerformance->sortByDesc('total_quizzes')->first(),
            'recent_performance' => $quizResults->take(5)->avg('score') ? round($quizResults->take(5)->avg('score'), 1) : 0,
            'total_questions_attempted' => $quizResults->sum('total_questions'),
            'total_questions_answered' => $quizResults->sum(function ($quiz) {
                return $quiz->answerDetails->count();
            })
        ];

        // Performance trend over time (last 10 quizzes)
        $performanceTrend = $quizResults->take(10)->reverse()->values()->map(function ($quiz, $index) {
            return [
                'quiz_number' => $index + 1,
                'score' => $quiz->score,
                'date' => $quiz->created_at->format('M d'),
                'category' => $quiz->category->name ?? 'Unknown'
            ];
        });

        $userRole = $user->accessRole;
        
        return view('medmastery.content.user-performance', compact(
            'categoryPerformance', 
            'overallStats', 
            'performanceTrend', 
            'user', 
            'userRole',
            'quizResults'
        ));
    }    /**
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

        // Get count of questions where the maximum score is less than 1 (still wrong)
        // This ensures that if a user has answered a question correctly later, it's not counted as wrong
        $wrongQuestionsCount = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
            ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
            ->where('med_mastery_answers.user_id', $user->id)
            ->where('med_mastery_answers.med_mastery_category_id', $categoryId)
            ->where('med_mastery_questions.is_active', true) // Only active questions
            ->selectRaw('med_mastery_answer_details.med_mastery_question_id, MAX(med_mastery_answer_details.score) as max_score')
            ->groupBy('med_mastery_answer_details.med_mastery_question_id')
            ->having('max_score', '<', 1)
            ->count();

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
            ->where('med_mastery_questions.is_active', true) // Only active questions
            ->where(function($query) use ($userId) {
                // Only include questions that user can access (own questions OR public questions)
                $query->where('med_mastery_questions.creator_id', $userId)
                      ->orWhere('med_mastery_questions.is_public', true);
            })
            ->selectRaw('med_mastery_answer_details.med_mastery_question_id, MAX(med_mastery_answer_details.score) as max_score')
            ->groupBy('med_mastery_answer_details.med_mastery_question_id')
            ->having('max_score', '<', 1)
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
     * Get correct question IDs for a user in a specific category (questions answered correctly)
     */
    private function getCorrectQuestionIds(string $categoryId, int $userId)
    {
        // Debug: Log the request
        Log::info('Getting correct question IDs', [
            'user_id' => $userId,
            'category_id' => $categoryId
        ]);

        $correctQuestionIds = MedMasteryAnswerDetail::join('med_mastery_answers', 'med_mastery_answer_details.med_mastery_answer_id', '=', 'med_mastery_answers.id')
            ->join('med_mastery_questions', 'med_mastery_answer_details.med_mastery_question_id', '=', 'med_mastery_questions.id')
            ->where('med_mastery_answers.user_id', $userId)
            ->where('med_mastery_answers.med_mastery_category_id', $categoryId)
            ->where('med_mastery_questions.is_active', true) // Only active questions
            ->where(function($query) use ($userId) {
                // Only include questions that user can access (own questions OR public questions)
                $query->where('med_mastery_questions.creator_id', $userId)
                      ->orWhere('med_mastery_questions.is_public', true);
            })
            ->selectRaw('med_mastery_answer_details.med_mastery_question_id, MAX(med_mastery_answer_details.score) as max_score')
            ->groupBy('med_mastery_answer_details.med_mastery_question_id')
            ->having('max_score', '>=', 1)
            ->pluck('med_mastery_answer_details.med_mastery_question_id')
            ->toArray();

        // Debug: Log the result
        Log::info('Correct question IDs result', [
            'question_ids' => $correctQuestionIds,
            'count' => count($correctQuestionIds),
            'user_id' => $userId,
            'category_id' => $categoryId
        ]);

        return $correctQuestionIds;
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

        // Get questions with their max scores
        $questionMaxScores = $allAnswers->groupBy('med_mastery_question_id')->map(function ($answers) {
            return [
                'max_score' => $answers->max('score'),
                'is_active' => $answers->first()['is_active'],
                'answers' => $answers
            ];
        });

        // Get wrong answers (max score < 1)
        $wrongAnswers = $questionMaxScores->filter(function ($data) {
            return $data['max_score'] < 1;
        });
        
        // Get active wrong answers
        $activeWrongAnswers = $wrongAnswers->filter(function ($data) {
            return $data['is_active'];
        });
        
        // Get unique question IDs
        $uniqueWrongQuestionIds = $activeWrongAnswers->keys();

        return response()->json([
            'user_id' => $user->id,
            'category_id' => $categoryId,
            'total_answers' => $allAnswers->count(),
            'total_questions_attempted' => $questionMaxScores->count(),
            'wrong_answers' => $wrongAnswers->count(),
            'active_wrong_answers' => $activeWrongAnswers->count(),
            'unique_wrong_questions' => $uniqueWrongQuestionIds->count(),
            'all_answers_sample' => $allAnswers->take(5),
            'question_max_scores_sample' => $questionMaxScores->take(5)->map(function ($data, $questionId) {
                return [
                    'question_id' => $questionId,
                    'max_score' => $data['max_score'],
                    'is_active' => $data['is_active'],
                    'answers_count' => $data['answers']->count()
                ];
            }),
            'wrong_answers_sample' => $wrongAnswers->take(5)->map(function ($data, $questionId) {
                return [
                    'question_id' => $questionId,
                    'max_score' => $data['max_score'],
                    'is_active' => $data['is_active'],
                    'answers_count' => $data['answers']->count()
                ];
            }),
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
