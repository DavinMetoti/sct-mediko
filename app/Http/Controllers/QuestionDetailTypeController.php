<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionDetailType;
use App\Models\User;

class QuestionDetailTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, 'question-detail-type.index']);

        if ($request->ajax()) {
            $data = QuestionDetailType::all();

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }

        return view('admin.question_detail_type');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Form creation tidak diperlukan untuk JSON response
        return response()->json([
            'message' => 'Form creation is not applicable in JSON response',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'minus_two' => 'required|string',
            'minus_one' => 'required|string',
            'zero' => 'required|string',
            'one' => 'required|string',
            'two' => 'required|string',
        ]);

        $questionDetailType = QuestionDetailType::create($validated);

        return response()->json([
            'message' => 'Data successfully created',
            'data' => $questionDetailType,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Temukan data berdasarkan ID
        $questionDetailType = QuestionDetailType::find($id);

        if (!$questionDetailType) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        // Kembalikan respons JSON
        return response()->json([
            'data' => $questionDetailType,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Form editing tidak diperlukan untuk JSON response
        return response()->json([
            'message' => 'Form editing is not applicable in JSON response',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'minus_two' => 'required|string',
            'minus_one' => 'required|string',
            'zero' => 'required|string',
            'one' => 'required|string',
            'two' => 'required|string',
        ]);

        // Temukan data berdasarkan ID
        $questionDetailType = QuestionDetailType::find($id);

        if (!$questionDetailType) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        // Update data
        $questionDetailType->update($validated);

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Data successfully updated',
            'data' => $questionDetailType,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan data berdasarkan ID
        $questionDetailType = QuestionDetailType::find($id);

        if (!$questionDetailType) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        // Hapus data
        $questionDetailType->delete();

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Data successfully deleted',
        ], 200);
    }
}
