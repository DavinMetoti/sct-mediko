<?php

namespace App\Http\Controllers;

use App\Models\QuestionDetail;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'question-detail.index']);

        return view('admin.question_detail');
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
            $validated = $request->validate([
                'id_question' => 'required|integer',
                'id_medical_field' => 'required|string',
                'clinical_case' => 'required|string',
                'new_information' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'discussion_image' => 'nullable|string',
                'panelist_answers_distribution' => 'required|json',
            ]);

            QuestionDetail::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail pertanyaan berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
