<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionBank;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $questionBanks = QuestionBank::all();

            return datatables()->of($questionBanks)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                        <button type="button" class="btn text-primary btn-edit"
                            data-bank-name="' . htmlspecialchars($row->bank_name, ENT_QUOTES, 'UTF-8') . '"
                            data-id="' . $row->id . '">
                            <i class="fa fa-edit"></i>
                        </button>
                        <a href="' . route("question-detail.show", $row->id) . '"
                            class="btn show-btn text-success"
                            data-id="' . $row->id . '"
                            data-bs-toggle="tooltip"
                            title="Lihat Soal">
                                <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn text-danger"
                            onclick="deleteQuestionBank(' . $row->id . ')">
                            <i class="fa fa-trash"></i>
                        </button>';
                })

                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.question_bank');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $questionBank = QuestionBank::create([
            'bank_name' => $request->bank_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question Bank berhasil dibuat!',
            'data' => $questionBank,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        $questionBank = QuestionBank::with(['questionDetails.medicalField','questionDetails.subTopic','questionDetails.questionType','questionDetails.question'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $questionBank,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $questionBank = QuestionBank::findOrFail($id);
        $questionBank->update([
            'bank_name' => $request->bank_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question Bank berhasil diupdate!',
            'data' => $questionBank,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $questionBank = QuestionBank::findOrFail($id);
        $questionBank->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question Bank berhasil dihapus!',
        ]);
    }

    public function searchQuestionBank(Request $request)
    {
        $query = $request->query('query');

        $questionBanks = QuestionBank::where('bank_name', 'like', '%' . $query . '%')
            ->orderBy('bank_name')
            ->limit(10)
            ->get();

        return response()->json($questionBanks->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->bank_name
            ];
        }));
    }

}
