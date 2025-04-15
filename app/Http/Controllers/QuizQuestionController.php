<?php

namespace App\Http\Controllers;

use App\Models\ColumnTitle;
use App\Models\QuestionBank;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'quiz-question.index']);

        $columnTitle = ColumnTitle::all();

        return view('quiz.content.layouts.create_quiz', [
            'columnTitle' => $columnTitle
        ]);
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
        try {
            // Validasi request
            $validated = $request->validate([
                'quiz_question_bank_id' => 'required|exists:quiz_question_banks,id',
                'medical_field_id' => 'required|exists:medical_fields,id',
                'column_title_id' => 'required|exists:column_titles,id',
                'clinical_case' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'new_information' => 'required|string',
                'timer' => 'required|integer|min:1',
                'explanation' => 'nullable|string',
                'answer' => 'required|array|min:1', // Jawaban wajib ada
                'answer.*.answer' => 'required|string',
                'answer.*.value' => 'required|integer',
                'answer.*.score' => 'required|numeric|min:0|max:1',
                'answer.*.panelist' => 'required|integer',
            ]);

            // Buat pertanyaan kuis dengan created_by dari auth()->id()
            $quizQuestion = QuizQuestion::create([
                'quiz_question_bank_id' => $validated['quiz_question_bank_id'],
                'medical_field_id' => $validated['medical_field_id'],
                'column_title_id' => $validated['column_title_id'],
                'clinical_case' => $validated['clinical_case'],
                'initial_hypothesis' => $validated['initial_hypothesis'],
                'new_information' => $validated['new_information'],
                'timer' => $validated['timer'],
                'created_by' => auth()->id(), // Ambil dari user yang sedang login
                'explanation' => $validated['explanation'] ?? null,
            ]);

            // Simpan jawaban ke dalam tabel `quiz_answers`
            foreach ($validated['answer'] as $ans) {
                QuizAnswer::create([
                    'quiz_question_id' => $quizQuestion->id,
                    'answer' => $ans['answer'],
                    'value' => $ans['value'],
                    'score' => $ans['score'],
                    'panelist' => $ans['panelist'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Quiz question created successfully',
                'data' => $quizQuestion->load('answers') // Load jawaban yang baru disimpan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz question',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $columnTitle = ColumnTitle::all();
        $questions = QuizQuestion::with('answers')->findOrFail($id);

        return view('quiz.content.layouts.edit_quiz', [
            'columnTitle' => $columnTitle,
            'questions'   => $questions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'quiz_question_bank_id' => 'required|exists:quiz_question_banks,id',
                'medical_field_id' => 'required|exists:medical_fields,id',
                'column_title_id' => 'required|exists:column_titles,id',
                'clinical_case' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'new_information' => 'required|string',
                'timer' => 'required|integer|min:1',
                'explanation' => 'nullable|string',
                'answer' => 'required|array|min:1',
                'answer.*.answer' => 'required|string',
                'answer.*.value' => 'required|integer',
                'answer.*.score' => 'required|numeric|min:0|max:1',
                'answer.*.panelist' => 'required|integer',
            ]);

            $quizQuestion = QuizQuestion::findOrFail($id);

            $quizQuestion->update([
                'quiz_question_bank_id' => $validated['quiz_question_bank_id'],
                'medical_field_id' => $validated['medical_field_id'],
                'column_title_id' => $validated['column_title_id'],
                'clinical_case' => $validated['clinical_case'],
                'initial_hypothesis' => $validated['initial_hypothesis'],
                'new_information' => $validated['new_information'],
                'timer' => $validated['timer'],
                'explanation' => $validated['explanation'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            $quizQuestion->answers()->delete();

            $quizQuestion->answers()->createMany($validated['answer']);

            return response()->json([
                'success' => true,
                'message' => 'Quiz question updated successfully',
                'data' => $quizQuestion->load('answers')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz question',
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
            $quizQuestion = QuizQuestion::findOrFail($id);
            $quizQuestion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quiz question deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quiz question.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
