<?php

namespace App\Http\Controllers;

use App\Models\TaskHistory;
use Illuminate\Http\Request;

class HistoryTryoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tryoutHistory = TaskHistory::with(['question' => function ($query) {
            $query->withCount('questionDetail');
        }])
            ->where('user_id', auth()->id())
            ->get();

        $tryoutHistory->each(function ($taskHistory) {
            if (isset($taskHistory->question->question_detail_count) && $taskHistory->question->question_detail_count > 0) {
                $taskHistory->total_score = ($taskHistory->score / $taskHistory->question->question_detail_count) * 100;
            } else {
                $taskHistory->total_score = 0;
            }
        });

        return view('public.tryout_history', [
            'tryoutHistory' => $tryoutHistory
        ]);
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
        //
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
