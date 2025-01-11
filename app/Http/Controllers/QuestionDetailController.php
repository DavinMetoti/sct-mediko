<?php

namespace App\Http\Controllers;

use App\Models\HeaderSubTopic;
use App\Models\MedicalField;
use App\Models\Question;
use App\Models\QuestionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'question-detail.index']);
        $topics = HeaderSubTopic::with('subTopics')->get();

        return view('admin.question_detail', compact(['topics']));
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
                'id_medical_field' => 'required|integer',
                'id_question_type' => 'required|integer',
                'id_sub_topic' => 'required|integer',
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
        try {
            $questionDetail = QuestionDetail::with('medicalField')->where('id_question', $id)->get();
            $question = Question::findOrFail($id);

            if (!$questionDetail) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Detail pertanyaan tidak ditemukan',
                ], 404);
            }

            return view('admin.question_detail_show',compact('question') ,[
                'questionDetail' => $questionDetail,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
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
        try {
            $validated = $request->validate([
                'id_question' => 'required|integer',
                'id_medical_field' => 'required|integer',
                'id_question_type' => 'required|integer',
                'id_sub_topic' => 'required|integer',
                'clinical_case' => 'required|string',
                'new_information' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'discussion_image' => 'nullable|string',
                'panelist_answers_distribution' => 'required|json',
            ]);

            $questionDetail = QuestionDetail::findOrFail($id);

            if (is_null($validated['discussion_image'])) {
                unset($validated['discussion_image']);
            }

            $questionDetail->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail pertanyaan berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $questionDetail = QuestionDetail::findOrFail($id);
            $questionDetail->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Detail pertanyaan berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function getQuestionDetailById($id)
    {
        $questions = Question::all();

        $medicalFields = MedicalField::all();

        $questionDetail = QuestionDetail::with(['question','medicalField','subTopic','questionType'])->findOrFail($id);

        $topics = HeaderSubTopic::with(['subTopics'])->get();

        return view('admin.question_detail_edit', compact('questionDetail','topics','questions','medicalFields'));
    }


}
