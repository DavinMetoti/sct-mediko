<?php

namespace App\Http\Controllers;

use App\Events\QuizUpdated;
use App\Models\QuizAttempt;
use App\Models\QuizSession;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class QuizSessionContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, 'quiz-session.index']);

        if ($request->ajax()) {
            $data = QuizSession::withCount('attempts')->get();
            return response()->json($data);
        }

        return view('quiz.content.layouts.quiz_session');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quiz.content.layouts.create_quiz_session');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'timer' => 'required|integer|min:1',
                'access_code' => 'required|string|max:10',
                'apply_all_timer' => 'boolean',
                'is_public' => 'boolean',
                'session_id' => 'required|string|max:14'
            ]);

            $quiz = QuizSession::create([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'timer' => $request->timer,
                'access_code' => $request->access_code,
                'apply_all_timer' => $request->apply_all_timer,
                'is_public' => $request->is_public,
                'session_id' => $request->session_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sesi kuis berhasil dibuat.',
                'data' => $quiz
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($err) => implode(', ', $err), $e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if ($request->ajax()) {
            $session = QuizSession::with(['questions.medicalField', 'classrooms'])->findOrFail($id);
            return response()->json($session);
        }

        return view('quiz.content.layouts.mapping_quiz_session');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'timer' => 'required|integer|min:1',
                'is_public' => 'boolean',
                'apply_all_timer' => 'boolean'
            ]);

            $quiz = QuizSession::findOrFail($id);

            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'timer' => $request->timer,
                'apply_all_timer' => $request->apply_all_timer,
                'is_public' => $request->is_public,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sesi kuis berhasil diperbarui.',
                'data' => $quiz
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($err) => implode(', ', $err), $e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi kuis tidak ditemukan.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $quizSession = QuizSession::findOrFail($id);

            $quizSession->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sesi kuis berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus sesi kuis'
            ], 500);
        }
    }

    public function attach(Request $request)
    {
        try {
            $validated = $request->validate([
                'quiz_sessions_id' => 'exists:quiz_sessions,id',
                'quiz_question_id' => 'array|required',
                'quiz_question_id.*' => 'exists:quiz_questions,id'
            ]);

            $quizSession = QuizSession::findOrFail($validated['quiz_sessions_id']);
            $quizSession->questions()->sync($validated['quiz_question_id']);

            return response()->json([
                'message' => 'Quiz session updated successfully'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Quiz session not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function attachClassroom(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|exists:quiz_sessions,id',
                'classroom_ids' => 'array',
                'classroom_ids.*' => 'exists:classrooms,id',
            ]);

            $session = QuizSession::findOrFail($validated['session_id']);
            $session->classrooms()->sync($validated['classroom_ids'] ?? []);

            return response()->json([
                'message' => 'Classroom berhasil dihubungkan ke sesi'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detachClassroom(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|exists:quiz_sessions,id',
                'classroom_id' => 'required|exists:classrooms,id',
            ]);

            $session = QuizSession::findOrFail($validated['session_id']);
            $session->classrooms()->detach($validated['classroom_id']);

            return response()->json([
                'message' => 'Classroom berhasil dihapus dari sesi'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkQuizSession(Request $request)
    {
        $isQuery = $request->query('access_code');
        $access_code = $request->query('access_code') ?? $request->input('access_code');

        if (!$access_code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode akses tidak boleh kosong.'
            ], 400);
        }

        $quizSession = QuizSession::withCount('questions')
            ->where('access_code', $access_code)
            ->first();

        if (!$quizSession || $quizSession->questions_count == 0) {
            if ($isQuery) {
                abort(403, "Kode akses tidak valid atau quiz tidak ditemukan.");
            }
            return response()->json([
                'success' => false,
                'message' => 'Kode akses tidak valid atau quiz tidak memiliki pertanyaan.'
            ], 404);
        }

        $now = now();

        if ($now->lt($quizSession->start_time)) {
            if ($isQuery) {
                abort(403, "Quiz belum dimulai. Silakan coba lagi nanti.");
            }
            return response()->json([
                'success' => false,
                'message' => 'Quiz belum dimulai. Silakan coba lagi nanti.'
            ], 403);
        }

        if ($now->gt($quizSession->end_time)) {
            if ($isQuery) {
                abort(403, "Quiz sudah berakhir.");
            }
            return response()->json([
                'success' => false,
                'message' => 'Quiz sudah berakhir.'
            ], 403);
        }

        $quizAttempt = QuizAttempt::create([
            'attempt_token' => Str::uuid()->toString(),
            'session_id'    => $quizSession->id,
            'user_id'       => auth()->id() ?? null,
            'name'          => auth()->user()->name ?? null,
            'score'         => 0,
        ]);

        Session::put('quiz_token', $quizAttempt->attempt_token);

        broadcast(new QuizUpdated($quizAttempt))->toOthers();

        if ($isQuery) {
            return redirect()->route('quiz-play.index', ['attempt_token' => $quizAttempt->attempt_token])
                ->with('success', 'Kode akses valid. Quiz attempt berhasil dibuat.');
        }

        // Jika request menggunakan POST (request body), kembalikan response JSON
        return response()->json([
            'success' => true,
            'message' => 'Kode akses valid. Quiz attempt berhasil dibuat.',
            'quiz_attempt' => $quizAttempt
        ],200);
    }

    public function sessionRank(Request $request, string $id)
    {
        $session = QuizSession::with('questions')->findOrFail($id);

        $questionCount = $session->questions ? $session->questions->count() : 0;

        $classroomId = $request->query('classroom_id');

        $attemptsQuery = \App\Models\QuizAttempt::with('classroom')
            ->where('session_id', $id)
            ->whereNotNull('score');

        if ($classroomId) {
            $attemptsQuery->where('classroom_id', $classroomId);
        }

        $attempts = $attemptsQuery
            ->orderByDesc('score')
            ->orderBy('completed_at')
            ->get()
            ->map(function ($attempt) use ($questionCount) {
                $attempt->percentage_score = $questionCount > 0
                    ? round(($attempt->score / $questionCount) * 100, 2)
                    : 0;
                return $attempt;
            });

        if ($request->ajax()) {
            return response()->json([
                'quizSessionId' => $id,
                'sessionRankList' => $attempts,
                'questionCount' => $questionCount
            ]);
        }

        return view('quiz.content.layouts.quiz_session_rank', compact('id', 'questionCount', 'attempts', 'session'));
    }
}
