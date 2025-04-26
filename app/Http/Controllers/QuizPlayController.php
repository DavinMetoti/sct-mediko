<?php

namespace App\Http\Controllers;

use App\Events\QuizUpdated;
use App\Models\QuizAttempt;
use App\Models\QuizUserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        return view('quiz.content.layouts.quiz_waiting_room', compact('token'));
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
        $token = Session::get('quiz_token');

        if (!$token) {
            abort(403, 'No active quiz session. Please enter access code.');
        }

        $attempt = QuizAttempt::where('attempt_token', $token)->first();

        if (!$attempt) {
            abort(403, 'Invalid or expired token');
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
            'session.questions', // hilangkan orderBy
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
            'score' => 'sometimes|nullable|numeric|min:0|max:100',
            'quiz_question_id' => 'sometimes|integer|exists:quiz_questions,id',
            'quiz_answer_id' => 'sometimes|integer|exists:quiz_answers,id'
        ]);

        $attempt = QuizAttempt::where('attempt_token', $id)->first();

        if (!$attempt) {
            return response()->json(['error' => 'Invalid or expired token'], 403);
        }

        // ❌ Cek apakah `completed_at` sudah terisi
        if (!is_null($attempt->completed_at)) {
            return response()->json(['error' => 'Quiz has already been completed. No further updates allowed.'], 403);
        }

        if ($request->has('name')) {
            $attempt->name = $request->name;
        }
        if ($request->has('score')) {
            $attempt->score += $request->score;
        }

        $attempt->save();

        // Periksa apakah `quiz_question_id` dan `quiz_answer_id` dikirim sebelum melanjutkan
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
                    'score' => $request->score ?? 0 // Default ke 0 jika `score` tidak dikirim
                ]);
            }

            $quizUserAnswer->save();
        }

        // 🔥 **Broadcast event setelah update**
        broadcast(new QuizUpdated($attempt))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Quiz attempt and answer updated successfully',
            'score' => $attempt->score,
            'answer_score' => isset($quizUserAnswer) ? $quizUserAnswer->score : null
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
