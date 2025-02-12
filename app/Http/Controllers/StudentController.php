<?php

namespace App\Http\Controllers;

use App\Models\MedicalField;
use App\Models\Question;
use App\Models\Setting;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'student.index']);

        $setting = Setting::all();

        $questionPublic = Question::where('status', 'active')
            ->where('is_public', 1)
            ->count();

        $questionUser = User::with('packages.questions')->findOrFail(auth()->id());

        $questionsInPackages = $questionUser->packages->sum(function ($package) {
            return $package->questions->count();
        });

        $questionActive_total = $questionPublic + $questionsInPackages;

        $taskHistory = TaskHistory::where('user_id', auth()->id())->count();

        $taskHistoryDetail = TaskHistory::with('question.questionDetail')
            ->where('user_id', auth()->id())
            ->get();

        $taskHistoryQuestionDetail = TaskHistory::with(['taskHistory', 'question', 'question.questionDetail' => function ($query) {
            $query->select('id_medical_field', 'panelist_answers_distribution');
        }])
            ->where('user_id', auth()->id())
            ->get();


        $taskHistoryQuestionDetail->each(function ($taskHistory) {
            optional($taskHistory->question)->questionDetail?->each(function ($questionDetail) {
                if (!empty($questionDetail->panelist_answers_distribution)) {
                    $questionDetail->panelist_answers_distribution = json_decode($questionDetail->panelist_answers_distribution, true);
                }
            });
        });


        $taskHistoryQuestionDetail->each(function ($taskHistory) {
            $taskHistory->question->questionDetail->each(function ($questionDetail) use ($taskHistory) {

                $maxValue = max(array_map('intval', $questionDetail->panelist_answers_distribution));


                $taskHistoryForDetail = $taskHistory->taskHistory->firstWhere('question_detail_id', $questionDetail->pivot->question_detail_id);


                if ($taskHistoryForDetail) {
                    $value = $questionDetail->panelist_answers_distribution[$taskHistoryForDetail->value??0] / $maxValue * 100;
                    $questionDetail->value = round($value, 2);
                } else {
                    $questionDetail->value = 0;
                }
            });
        });

        $medicalField = MedicalField::all();


        $total_akhir_sum = 0;
        $total_akhir_count = 0;


        foreach ($taskHistoryDetail as $item) {
            $total_question_detail = $item->question->questionDetail->count() ?? 0;

            if ($total_question_detail > 0) {
                $total_akhir = ($item->score / $total_question_detail) * 100;
            } else {
                $total_akhir = 0;
            }

            $item->total_akhir = $total_akhir;
            $total_akhir_sum += $total_akhir;
            $total_akhir_count++;
        }


        $average_total_akhir = $total_akhir_count > 0 ? ($total_akhir_sum / $total_akhir_count) : 0;


        $medicalField->each(function ($field) use ($taskHistoryQuestionDetail) {
            $fieldTaskDetails = $taskHistoryQuestionDetail->filter(function ($taskHistory) use ($field) {
                return $taskHistory->question->questionDetail->contains('id_medical_field', $field->id);
            });

            $totalValue = 0;
            $count = 0;

            $fieldTaskDetails->each(function ($taskHistory) use ($field, &$totalValue, &$count) {
                $taskHistory->question->questionDetail->each(function ($questionDetail) use ($field, &$totalValue, &$count) {
                    if ($questionDetail->id_medical_field == $field->id && isset($questionDetail->value)) {
                        $totalValue += $questionDetail->value;
                        $count++;
                    }
                });
            });

            $field->average = $count > 0 ? round($totalValue / $count, 2) : 0;
        });

        return view('public.dashboard', compact([
            'questionActive_total',
            'taskHistory',
            'average_total_akhir',
            'taskHistoryQuestionDetail',
            'medicalField',
            'setting'
        ]));
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

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
}
