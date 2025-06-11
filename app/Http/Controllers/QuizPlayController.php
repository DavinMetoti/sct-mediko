<?php

namespace App\Http\Controllers;

use App\Events\QuizUpdated;
use App\Models\Classroom;
use App\Models\QuizAttempt;
use App\Models\QuizUserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;

class QuizPlayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = Session::get('quiz_token');

        if (!$token) {
            abort(403, 'No active quiz session. Please enter access code.');
        }

        $attempt = QuizAttempt::where('attempt_token', $token)->first();

        if (!$attempt) {
            abort(403, 'Invalid or expired token');
        }

        $session = $attempt->session()->with('classrooms')->withCount('questions')->first();

        $now = Carbon::now();

        $filteredClassrooms = $session?->classrooms->filter(function ($classroom) use ($now) {

            return $now->between($classroom->start_time, $classroom->end_time);
        });

        return view('quiz.content.layouts.quiz_waiting_room', [
            'token' => $token,
            'session' => $session,
            'classrooms' => $filteredClassrooms ?? collect()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $token = Session::get('quiz_token');

        if (!$token) {
            abort(403, 'No active quiz session. Please enter access code.');
        }

        $attempt = QuizAttempt::where('attempt_token', $token)->first();

        if (!$attempt) {
            abort(403, 'Invalid or expired token');
        }

        $now = Carbon::now();


        if (request()->ajax()) {
            $session = $attempt->session()->with('classrooms')->first();

            $filteredClassrooms = $session?->classrooms
                ->filter(function ($classroom) use ($now) {
                    return $now->between($classroom->start_time, $classroom->end_time);
                })
                ->values()
                ->toArray();

            return response()->json([
                'attempt' => $attempt,
                'session' => $session,
                'classrooms' => $filteredClassrooms
            ]);
        }

        return view('quiz.content.layouts.quiz_play', compact('attempt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $token = Session::get('quiz_token');

        $attempt = QuizAttempt::with([
            'session.questions' => function ($query) {
                $query->orderBy('id', 'asc');
            },
            'session.questions.columnTitle',
            'session.questions.answers',
            'userAnswer'
        ])->findOrFail($id);

        return view('quiz.content.layouts.quiz_result', compact('attempt'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'classroom_id' => 'sometimes|exists:classrooms,id',
            'score' => 'sometimes|nullable|numeric|min:0|max:100',
            'quiz_question_id' => 'sometimes|integer|exists:quiz_questions,id',
            'quiz_answer_id' => 'sometimes|integer|exists:quiz_answers,id'
        ]);

        $attempt = QuizAttempt::where('attempt_token', $id)->first();

        if (!$attempt) {
            return response()->json(['error' => 'Invalid or expired token'], 403);
        }


        if (!is_null($attempt->completed_at)) {
            return response()->json(['error' => 'Quiz has already been completed. No further updates allowed.'], 403);
        }

        if ($request->has('name')) {
            $attempt->name = $request->name;
        }
        if ($request->has('score')) {
            $attempt->score += $request->score;
        }
        if ($request->has('classroom_id')) {
            $attempt->classroom_id = $request->classroom_id;
        }

        $attempt->save();


        if ($request->has(['quiz_question_id', 'quiz_answer_id'])) {
            $quizUserAnswer = QuizUserAnswer::where([
                'quiz_attempts_id' => $attempt->id,
                'quiz_question_id' => $request->quiz_question_id,
                'quiz_answer_id' => $request->quiz_answer_id
            ])->first();

            if ($quizUserAnswer) {
                if ($request->has('score')) {
                    $quizUserAnswer->score += $request->score;
                }
            } else {
                $quizUserAnswer = new QuizUserAnswer([
                    'quiz_attempts_id' => $attempt->id,
                    'quiz_question_id' => $request->quiz_question_id,
                    'quiz_answer_id' => $request->quiz_answer_id,
                    'score' => $request->score ?? 0
                ]);
            }

            $quizUserAnswer->save();
        }


        broadcast(new QuizUpdated($attempt))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Quiz attempt and answer updated successfully',
            'score' => $attempt->score,
            'answer_score' => isset($quizUserAnswer) ? $quizUserAnswer->score : null
        ]);
    }

    public function print(string $id)
    {
        // Jika ada parameter preview, tampilkan HTML untuk Browsershot
        if (request()->has('preview')) {
            $attempt = \App\Models\QuizAttempt::with([
                'session.questions' => function ($query) {
                    $query->orderBy('id', 'asc');
                },
                'session.questions.columnTitle',
                'session.questions.answers',
                'userAnswer'
            ])->findOrFail($id);

            $printDate = now()->format('d-m-Y H:i');
            return view('quiz.print.index', compact('attempt', 'printDate'));
        }

        // Header HTML dengan logo dan judul quiz
        $attempt = \App\Models\QuizAttempt::with([
            'session'
        ])->findOrFail($id);

        $logoPath = public_path('assets/images/logo-mediko.webp');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/webp;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $footerHtml = '<div style="width:100%;font-size:11px;padding:4px 24px 0 24px;display:flex;justify-content:space-between;">
            <span>Cetak: '.now()->format('d-m-Y H:i').'</span>
            <span>Halaman <span class="pageNumber"></span> / <span class="totalPages"></span></span>
        </div>';

        try {
            $pdf = \Spatie\Browsershot\Browsershot::url(route('quiz-play.print', ['id' => $id, 'preview' => 1]))
                ->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->setChromePath('/usr/bin/google-chrome')
                ->addChromiumArguments([
                    '--no-sandbox',
                    '--disable-gpu',
                    '--user-data-dir=/tmp/chrome',
                ])
                ->waitUntilNetworkIdle()
                ->showBrowserHeaderAndFooter()
                ->format('A4')
                ->margins(5, 10, 10, 10) // top, right, bottom, left dalam mm
                ->showBackground()
                ->footerHtml($footerHtml)
                ->pdf();

            return response()->json([
                'success' => true,
                'file' => base64_encode($pdf),
                'filename' => "hasil-kuiz-" . ($attempt->name ?? 'tanpa-nama') . ".pdf"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate PDF: ' . $e->getMessage()
            ]);
        }

    }
}
