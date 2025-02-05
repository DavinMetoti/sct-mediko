<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Models\Package;
use App\Models\Question;
use App\Models\QuestionDetail;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;


class QuestionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'question.index']);
        return view('admin.make_question');
    }

    public function store(StoreQuestionRequest $request): JsonResponse
    {
        try {
            $question = Question::create([
                'question' => $request->question,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'time' => $request->time,
                'end_time' => $request->end_time,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'is_public' => $request->is_public,
                'thumbnail' => $request->thumbnail,
            ]);

            return response()->json([
                'message' => 'Question created successfully.',
                'data' => $question,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create question.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request,$id)
    {
        try {
            $question = Question::findOrFail($id);

            $question->update($request->only([
                'question',
                'description',
                'start_time',
                'time',
                'end_time',
                'status',
                'is_public',
                'thumbnail',
            ]));

            return response()->json([
                'message' => 'Question updated successfully.',
                'data' => $question,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update question.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $question = Question::findOrFail($id);

            return response()->json([
                'message' => 'data ditemukan',
                'data' => $question,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => 'Gagal menemukan data'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $id_question = $request->query('question');

        if ($request->ajax()) {

            $question = Question::with(['questionDetail.medicalField', 'questionDetail.subTopic', 'questionDetail.questionType','questionDetail.questionBank'])
                ->findOrFail($id_question);


            return datatables()->of($question->questionDetail)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                        <button type="button" class="btn text-gray btn-swap" data-id="' . $row->id . '">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.question_bank_mapping', [
            'id_question' => $id_question
        ]);
    }

    public function getQuestionByName(Request $request)
    {
        try {

            $search = $request->input('search');


            $questions = Question::where('question', 'like', "%{$search}%")
                ->limit(10)
                ->get();


            if ($questions->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'message' => 'Data ditemukan',
                'data' => $questions,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getQuestionData()
    {
        $questions = Question::with('creator')->get();
        return datatables()->of($questions)
        ->addIndexColumn()
        ->addColumn('actions', function ($question) {
            return '
                <div class="dropdown">
                    <button class="btn btn-sm btn-light text-dark dropdown-toggle" type="button" id="dropdownMenuButton' . $question->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $question->id . '">
                        <li>
                            <a href="' . route('question.create', ['question' => $question->id]) . '" class="dropdown-item">
                                <i class="fas fa-bank text-dark"></i> Bank Soal
                            </a>
                        </li>
            ' .
            ($question->status != 'active' ? '
                        <li>
                            <button class="dropdown-item active-btn" data-id="' . $question->id . '">
                                <i class="fas fa-check text-primary"></i> Activate
                            </button>
                        </li>
            ' : '') .
            ($question->status != 'archived' ? '
                        <li>
                            <button class="dropdown-item archive-btn" data-id="' . $question->id . '">
                                <i class="fas fa-archive text-warning"></i> Archive
                            </button>
                        </li>
            ' : '') .
            ($question->status != 'inactive' ? '
                        <li>
                            <button class="dropdown-item nonactive-btn" data-id="' . $question->id . '">
                                <i class="fas fa-ban text-secondary"></i> Deactivate
                            </button>
                        </li>
            ' : '') . '
                        <li>
                            <button class="dropdown-item edit-btn" data-id="' . $question->id . '">
                                <i class="fas fa-edit text-success"></i> Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item delete-btn" data-id="' . $question->id . '">
                                <i class="fas fa-trash text-danger"></i> Delete
                            </button>
                        </li>
                    </ul>
                </div>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);

    }

    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();

            return response()->json([
                'message' => 'Question deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete question.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showQuestion(Request $request)
    {
        $searchQuery = $request->input('search');

        $questions = Question::where('is_public', 1)
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('question', 'like', "%{$searchQuery}%");
            })
            ->get();

        $user = auth()->user();

        if ($user->id_access_role == 1) {
            // Admin: Retrieve all packages with associated questions and details
            $packages = Package::with(['questions.questionDetail'])->get();
        } else {
            $userPackages = User::where('id', $user->id)
                ->with(['packages.questions' => function($query) {
                }])
                ->first();

                $packages = $userPackages->packages->filter(function ($package) use ($searchQuery) {
                    if ($package->questions->isNotEmpty()) {
                        $package->questions = $package->questions->filter(function ($question) use ($searchQuery) {
                            return stripos($question->question, $searchQuery) !== false;
                        });
                    }

                    return $package->questions;
                });
        }


        $this->authorize('viewAny', [User::class, 'question-list.index']);

        return view('admin.list_questions', compact('questions', 'packages', 'searchQuery'));
    }


    public function showQuestionPreview($id)
    {
        $question = Question::with('questionDetail')->findOrFail($id);

        $questionDetail = $question->questionDetail->count();

        $checkTryout = TaskHistory::where('question_id', $id)
                                ->where('user_id', auth()->id())
                                ->first();

        unset($question->questionDetail);

        return view('admin.preview_question', compact('question', 'questionDetail','checkTryout'));
    }



    public function uploadImage(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads/images', 'public');


                return response()->json([
                    'link' => asset('storage/' . $path),
                ]);
            } else {
                return response()->json(['error' => 'No file uploaded'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads/file', 'public');


                return response()->json([
                    'link' => asset('storage/' . $path),
                ]);
            } else {
                return response()->json(['error' => 'No file uploaded'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function attachQuestionDetails(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'question_detail_ids' => 'required|array',
            'question_detail_ids.*' => 'exists:question_details,id',
        ]);

        $question = Question::findOrFail($validatedData['question_id']);

        $existingDetails = $question->questionDetail->pluck('id')->toArray();
        $newDetails = array_diff($validatedData['question_detail_ids'], $existingDetails);

        if (count($newDetails) > 0) {
            $question->questionDetail()->attach($newDetails);
        }

        return response()->json([
            'message' => 'Question details attached successfully.',
            'attached_question_details' => $newDetails,
        ]);
    }

    public function detachQuestionDetails(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'question_detail_ids' => 'required|array',
            'question_detail_ids.*' => 'exists:question_details,id',
        ]);

        $question = Question::findOrFail($validatedData['question_id']);

        $existingDetails = $question->questionDetail->pluck('id')->toArray();

        $detailsToDetach = array_intersect($validatedData['question_detail_ids'], $existingDetails);

        if (count($detailsToDetach) > 0) {
            $question->questionDetail()->detach($detailsToDetach);
        }

        return response()->json([
            'message' => 'Question details detached successfully.',
            'detached_question_details' => $detailsToDetach,
        ]);
    }

}
