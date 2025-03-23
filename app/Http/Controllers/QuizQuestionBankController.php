<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use App\Models\QuizQuestionBank;
use Illuminate\Http\Request;

class QuizQuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->apiIndex();
        }

        return view('quiz.content.layouts.create_quiz_question_bank');
    }


    public function apiIndex()
    {
        try {
            $quizQuestionBanks = QuizQuestionBank::withCount('questions')->get();

            return response()->json([
                'success' => true,
                'data' => $quizQuestionBanks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
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
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|unique:quiz_question_banks,name'
        ]);

        try {
            $quizQuestionBank = QuizQuestionBank::create([
                'name' => $validated['name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank Soal berhasil ditambahkan',
                'data' => $quizQuestionBank
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Bank Soal',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $quizBank = QuizQuestionBank::with(['questions.answers', 'questions.medicalField'])->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'quizQuestions' => $quizBank->questions
            ]);
        }

        return view('quiz.content.layouts.quiz_question_list', [
            'quizQuestions' => $quizBank->questions,
        ]);
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
        $validated = $request->validate([
            'name' => 'required|string|unique:quiz_question_banks,name,' . $id
        ]);

        try {
            $quizQuestionBank = QuizQuestionBank::findOrFail($id);

            $quizQuestionBank->update([
                'name' => $validated['name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank Soal berhasil diperbarui',
                'data' => $quizQuestionBank
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Bank Soal',
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
            $quizQuestionBank = QuizQuestionBank::findOrFail($id);

            $quizQuestionBank->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bank Soal berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Bank Soal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
