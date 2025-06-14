<?php

namespace App\Http\Controllers;

use App\Models\ColumnTitle;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionBank;
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
                'quiz_question_bank_id' => 'nullable',
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
                'uploaded_image_base64' => 'nullable|string',
                'new_bank'=>'nullable|string',
                'rationale' => 'nullable|string',
            ]);

            // Jika new_bank diisi, buat bank baru dan gunakan id-nya
            if (!empty($validated['new_bank'])) {
                $quizQuestionBank = new QuizQuestionBank();
                $quizQuestionBank->name = $validated['new_bank'];
                $quizQuestionBank->save();
                $validated['quiz_question_bank_id'] = $quizQuestionBank->id;
            } else {
                // Jika tidak ada new_bank, pastikan quiz_question_bank_id ada (boleh null)
                $validated['quiz_question_bank_id'] = $validated['quiz_question_bank_id'] ?? null;
            }

            // Ambil soal terakhir dengan kombinasi yang sama
            $quizQuestion = QuizQuestion::where('quiz_question_bank_id', $validated['quiz_question_bank_id'])
                ->where('medical_field_id', $validated['medical_field_id'])
                ->where('column_title_id', $validated['column_title_id'])
                ->where('new_information', $validated['new_information'])
                ->where('initial_hypothesis', $validated['initial_hypothesis'])
                ->where('created_by', auth()->id())
                ->first();

            $shouldUpdate = false;
            if ($quizQuestion) {
                // Cek apakah semua field (selain jawaban) sama persis
                $shouldUpdate =
                    $quizQuestion->clinical_case === $validated['clinical_case'] &&
                    $quizQuestion->initial_hypothesis === $validated['initial_hypothesis'] &&
                    $quizQuestion->new_information === $validated['new_information'] &&
                    $quizQuestion->new_information == $validated['new_information'] &&
                    $quizQuestion->uploaded_image_base64 === ($validated['uploaded_image_base64'] ?? null);
            }

            if ($quizQuestion && $shouldUpdate) {
                $quizQuestion->update([
                    'clinical_case' => $validated['clinical_case'],
                    'initial_hypothesis' => $validated['initial_hypothesis'],
                    'new_information' => $validated['new_information'],
                    'timer' => $validated['timer'],
                    'explanation' => $validated['explanation'] ?? null,
                    'uploaded_image_base64' => $validated['uploaded_image_base64'] ?? null,
                    'rationale' => $validated['rationale'] ?? null,
                ]);

                // Hapus jawaban lama dan simpan jawaban baru
                $quizQuestion->answers()->delete();
                $quizQuestion->answers()->createMany($validated['answer']);

                $message = 'Quiz question updated successfully';
            } else {
                // Buat pertanyaan kuis baru
                $quizQuestion = QuizQuestion::create([
                    'quiz_question_bank_id' => $validated['quiz_question_bank_id'],
                    'medical_field_id' => $validated['medical_field_id'],
                    'column_title_id' => $validated['column_title_id'],
                    'clinical_case' => $validated['clinical_case'],
                    'initial_hypothesis' => $validated['initial_hypothesis'],
                    'new_information' => $validated['new_information'],
                    'timer' => $validated['timer'],
                    'created_by' => auth()->id(),
                    'explanation' => $validated['explanation'] ?? null,
                    'uploaded_image_base64' => $validated['uploaded_image_base64'] ?? null,
                    'rationale' => $validated['rationale'] ?? null,
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

                $message = 'Quiz question created successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $quizQuestion->load('answers')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create or update quiz question',
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
                'uploaded_image_base64' => 'nullable|string',
                'rationale' => 'nullable|string',
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
                'uploaded_image_base64' => $validated['uploaded_image_base64'] ?? null,
                'rationale' => $validated['rationale'] ?? null,
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
