<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

    public function getQuestionByName(Request $request)
    {
        try {
            // Ambil parameter pencarian dari request
            $search = $request->input('search');

            // Cari pertanyaan yang sesuai dengan query pencarian
            $questions = Question::where('question', 'like', "%{$search}%")
                ->limit(10)
                ->get();

            // Periksa apakah data ditemukan
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
                $actions = '';

                if ($question->status != 'active') {
                    $actions .= '
                        <button class="btn btn-sm btn-primary active-btn" data-id="' . $question->id . '" data-bs-toggle="tooltip" title="Edit">
                            <i class="fas fa-check"></i>
                        </button>
                    ';
                }

                if ($question->status != 'archived') {
                    $actions .= '
                        <button class="btn btn-sm btn-warning archive-btn" data-id="' . $question->id . '" data-bs-toggle="tooltip" title="Archive">
                            <i class="fas fa-archive"></i>
                        </button>
                    ';
                }

                if ($question->status != 'inactive') {
                    $actions .= '
                        <button class="btn btn-sm btn-secondary nonactive-btn" data-id="' . $question->id . '" data-bs-toggle="tooltip" title="Non-active">
                            <i class="fas fa-ban"></i>
                        </button>
                    ';
                }

                $actions .= '
                    <button class="btn btn-sm btn-success edit-btn" data-id="' . $question->id . '" data-bs-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                ';

                $actions .= '
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $question->id . '" data-bs-toggle="tooltip" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                ';

                return $actions;
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

    public function showQuestion()
    {
        $questions = Question::where('is_public', 1)->get();

        $this->authorize('viewAny', [User::class, 'question-list.index']);

        return view('admin.list_questions', compact('questions'));
    }

    public function showQuestionPreview($id)
    {
        $question = Question::findOrFail($id);

        return view('admin.preview_question',compact('question'));

    }


}
