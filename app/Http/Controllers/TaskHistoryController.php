<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionDetail;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Http\Request;

class TaskHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            // Validate incoming request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'question_id' => 'required|exists:questions,id',
                'sisa_waktu' => 'required|integer|min:0',
                'score' => 'required|integer|min:0',
                'status' => 'required|in:in_progress,completed,failed',
            ]);

            // Check if there's a completed task history for the same user and question
            $finishingTaskHistory = TaskHistory::where('user_id', $validatedData['user_id'])
                ->where('question_id', $validatedData['question_id'])
                ->where('status', 'completed')
                ->first();

            if ($finishingTaskHistory) {
                // Delete the completed task history
                $finishingTaskHistory->delete();

                return response()->json([
                    'message' => 'A completed task history was found and deleted.',
                ], 200);
            }

            // Check if there's an in-progress task history for the same user and question
            $existingTaskHistory = TaskHistory::where('user_id', $validatedData['user_id'])
                ->where('question_id', $validatedData['question_id'])
                ->where('status', 'in_progress')
                ->first();

            if ($existingTaskHistory) {
                return response()->json([
                    'message' => 'Task history already in progress.',
                    'data' => $existingTaskHistory,
                ], 200);
            }

            // Create a new task history record
            $taskHistory = TaskHistory::create($validatedData);

            return response()->json([
                'message' => 'Task history created successfully.',
                'data' => $taskHistory,
            ], 201);

        } catch (\Exception $e) {
            // Catch and return any exceptions
            return response()->json([
                'message' => 'Failed to create task history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        {
            $tryout = TaskHistory::with('TaskHistory')->where('id', $id)
            ->first();

            $question = Question::with([
                'questionDetail:id,id'
            ])
            ->where('id', $tryout->question_id)
            ->first();

            if ($request->ajax()) {
                return response()->json([
                    'question' => $question,
                    'tryout' => $tryout,
                ]);
            }


            $user = User::with('userDetail')->findOrFail(auth()->id());

            return view('public.tryout_result', compact(['question','user','tryout']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        {
            $questionDetail = QuestionDetail::with([
                'medicalField',
                'subTopic',
                'questionType',
                'columnTitle'
            ])->findOrFail($id);

            return response()->json([
                'data' => $questionDetail,
                'status' => 'success'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'sisa_waktu' => 'sometimes|integer|min:0',
                'score' => 'sometimes|integer|min:0',
                'status' => 'sometimes|in:in_progress,completed,failed',
            ]);

            $taskHistory = TaskHistory::findOrFail($id);

            if ($request->has('status') && $request->status === 'completed') {
                $validatedData['completed_at'] = now();
            }

            $taskHistory->update($validatedData);

            return response()->json([
                'message' => 'Task history updated successfully.',
                'data' => $taskHistory,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update task history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
