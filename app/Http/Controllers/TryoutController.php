<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionDetail;
use App\Models\Setting;
use App\Models\TaskHistory;
use App\Models\TaskHistoryDetail;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class TryoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $idQuestion)
    {
        $token = $request->query('token');
        if ($token !== auth()->user()->session_token) {
            abort(403, 'Mohon maaf sesi ini tidak valid');
        }

        $user = User::with('accessRole')->findOrFail(auth()->id());

        $tryout = TaskHistory::where('id', $idQuestion)
        ->first();

        if ($tryout->status == "completed") {
            if($user->accessRole->access == "private"){
                return redirect()->route('dashboard.index');
            } else {
                return redirect()->route('student.index');
            }
        }

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
        $formReport = Setting::where('key', 'form_aduan')->get();


        $user = User::with('userDetail')->findOrFail(auth()->id());

        return view('public.tryout', compact(['question','user','tryout','formReport']));
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
        try {
            $validatedData = $request->validate([
                'task_history_id' => 'required|exists:task_histories,id',
                'question_detail_id' => 'required|exists:question_details,id',
                'value' => 'nullable|integer',
                'status' => 'required|in:mark,completed',
            ]);

            $taskHistory = TaskHistory::find($validatedData['task_history_id']);

            if ($taskHistory->status == "completed") {
                return response()->json([
                    'message' => 'You has completed',
                    'error' => 'Task completed',
                ], 500);
            } else {
                $taskHistoryDetail = TaskHistoryDetail::where('task_history_id', $validatedData['task_history_id'])
                ->where('question_detail_id', $validatedData['question_detail_id'])
                ->first();

                if ($taskHistoryDetail) {

                    $taskHistoryDetail->update([
                        'value' => $validatedData['value'],
                        'status' => $validatedData['status'],
                    ]);
                    $message = 'Task history detail updated successfully.';
                } else {

                    $taskHistoryDetail = TaskHistoryDetail::create($validatedData);
                    $message = 'Task history detail created successfully.';
                }

                $answerListHistory = TaskHistoryDetail::where('task_history_id', $validatedData['task_history_id'])->get();

                $totalScore = 0;

                foreach ($answerListHistory as $historyDetail) {
                    $panelistAnswersDistribution = QuestionDetail::where('id', $historyDetail->question_detail_id)
                        ->value('panelist_answers_distribution');

                    $key_answer = json_decode($panelistAnswersDistribution);

                    if (is_object($key_answer)) {
                        $values = array_map('intval', (array) $key_answer);
                        $maxValue = max($values);

                        $formattedKeyAnswer = new stdClass();

                        foreach ($key_answer as $key => $value) {
                            $formattedKeyAnswer->{$key} = [
                                'value' => $value,
                                'skor' => $value / $maxValue,
                            ];
                        }

                        $valueKey = $historyDetail->value;

                        if (isset($formattedKeyAnswer->{$valueKey})) {
                            $answer_selected = $formattedKeyAnswer->{$valueKey};
                            $scoreToAdd = $answer_selected['skor'];

                            $totalScore += $scoreToAdd;

                        } else {
                            return response()->json([
                                'message' => 'Failed to process task history detail.',
                                'error' => 'Invalid value for calculation.',
                            ], 500);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Failed to process task history detail.',
                            'error' => 'Invalid panelist answers distribution.',
                        ], 500);
                    }
                }


                $taskHistory->update([
                    'score' => $totalScore,
                ]);
            }

            return response()->json([
                'message' => $message,
                'data' => $taskHistoryDetail,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process task history detail.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $questionDetail = QuestionDetail::with([
            'medicalField',
            'subTopic',
            'questionType',
            'columnTitle'
        ])->findOrFail($id);

        $questionDetail->makeHidden('panelist_answers_distribution');

        return response()->json([
            'data' => $questionDetail,
            'status' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function getHistoryAnswer(Request $request)
    {
        $tryout = TaskHistory::with('TaskHistory')->where('id', $request->task_history_id)
            ->first();

        if ($tryout) {
            return response()->json([
                'success' => true,
                'data' => $tryout
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Task history not found'
            ]);
        }
    }

}
