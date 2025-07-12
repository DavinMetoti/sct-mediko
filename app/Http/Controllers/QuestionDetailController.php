<?php

namespace App\Http\Controllers;

use App\Models\ColumnTitle;
use App\Models\HeaderSubTopic;
use App\Models\MedicalField;
use App\Models\Question;
use App\Models\QuestionBank;
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
        $questionBank = QuestionBank::all();
        $columnTitle = ColumnTitle::all();

        return view('admin.question_detail', compact(['topics', 'questionBank','columnTitle']));
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
                'question_bank_id' => 'required|integer',
                'id_medical_field' => 'required|integer',
                'id_question_type' => 'required|integer',
                'id_sub_topic' => 'required|integer',
                'column_title_id' => 'required|integer',
                'clinical_case' => 'required|string',
                'new_information' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'discussion_image' => 'nullable|string',
                'panelist_answers_distribution' => 'required|json',
                'rationale' => 'required|string',
            ]);

            $questionDetail = QuestionDetail::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail pertanyaan berhasil ditambahkan',
                'data' => [
                    'id' => $questionDetail->id
                ]
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
        $questionDetail = QuestionDetail::with('medicalField')->where('question_bank_id', $id)->get();
        $question = QuestionBank::findOrFail($id);

        if (!$questionDetail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Detail pertanyaan tidak ditemukan',
            ], 404);
        }

        return view('admin.question_detail_show',compact('question') ,[
            'questionDetail' => $questionDetail,
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
        try {
            $validated = $request->validate([
                'question_bank_id' => 'required|integer',
                'id_medical_field' => 'required|integer',
                'id_question_type' => 'required|integer',
                'id_sub_topic' => 'required|integer',
                'column_title_id' => 'required|integer',
                'clinical_case' => 'required|string',
                'new_information' => 'required|string',
                'initial_hypothesis' => 'required|string',
                'discussion_image' => 'nullable|string',
                'panelist_answers_distribution' => 'required|json',
                'rationale' => 'required|string',
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
        $questionBank = QuestionBank::all();

        $medicalFields = MedicalField::all();

        $questionDetail = QuestionDetail::with(['question','medicalField','subTopic','questionType'])->findOrFail($id);

        $columnTitle = ColumnTitle::all();

        $topics = HeaderSubTopic::with(['subTopics'])->get();

        return view('admin.question_detail_edit', compact('questionDetail','topics','questionBank','medicalFields','columnTitle'));
    }

    /**
     * Upload flashcard PDF for panelist
     */
    public function uploadFlashcard(Request $request)
    {
        try {
            $validated = $request->validate([
                'panelist' => 'required|string',
                'flashcard_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
                'question_detail_id' => 'required|integer|exists:question_details,id',
            ]);

            $file = $request->file('flashcard_file');
            $panelist = $validated['panelist'];
            $questionDetailId = $validated['question_detail_id'];

            // Ensure flashcards directory exists
            $flashcardsPath = storage_path('app/public/flashcards');
            if (!is_dir($flashcardsPath)) {
                mkdir($flashcardsPath, 0755, true);
            }

            // Create filename with timestamp
            $timestamp = now()->format('YmdHis');
            $filename = "flashcard_panelist_{$panelist}_{$timestamp}.pdf";
            
            // Store file in public/storage/flashcards directory
            $path = $file->storeAs('flashcards', $filename, 'public');

            // Create flashcard record
            $flashcard = new \App\Models\QuestionDetailFlashCard();
            $flashcard->question_detail_id = $questionDetailId; // Required, must exist
            $flashcard->path = $path;
            $flashcard->panelist = $panelist;
            $flashcard->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Flashcard berhasil diupload',
                'data' => [
                    'id' => $flashcard->id,
                    'path' => $path,
                    'panelist' => $panelist,
                    'question_detail_id' => $questionDetailId,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get flashcards for a question detail
     */
    public function getFlashcards(string $id)
    {
        try {
            $questionDetail = QuestionDetail::findOrFail($id);
            $flashcards = $questionDetail->flashCards()->orderBy('panelist')->get();

            return response()->json([
                'status' => 'success',
                'data' => $flashcards->map(function($flashcard) {
                    return [
                        'id' => $flashcard->id,
                        'panelist' => $flashcard->panelist,
                        'path' => $flashcard->path,
                        'url' => asset('storage/' . $flashcard->path),
                        'created_at' => $flashcard->created_at->format('d/m/Y H:i'),
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get flashcards by panelist value
     */
    public function getFlashcardsByPanelist(Request $request)
    {
        try {
            $validated = $request->validate([
                'panelist' => 'required|string',
            ]);

            $flashcards = \App\Models\QuestionDetailFlashCard::where('panelist', $validated['panelist'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $flashcards->map(function($flashcard) {
                    return [
                        'id' => $flashcard->id,
                        'panelist' => $flashcard->panelist,
                        'path' => $flashcard->path,
                        'url' => asset('storage/' . $flashcard->path),
                        'created_at' => $flashcard->created_at->format('d/m/Y H:i'),
                        'question_detail_id' => $flashcard->question_detail_id,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

}
