<?php

namespace App\Http\Controllers;

use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('accessRole')->findOrFail(auth()->user()->id);

        $sessionsQuery = QuizSession::with('libraries')
            ->withCount('questions')
            ->withCount('attempts');

        if ($user->accessRole->access !== 'private') {
            $sessionsQuery->where('is_public', 1);
        }

        $sessions = $sessionsQuery->get()->filter(function ($session) {
            $now = now();
            return $session->questions_count > 0
                && $now->gte($session->start_time)
                && $now->lte($session->end_time);
        });

        return view('quiz.content.layouts.dashboard', [
            "sessions_list" => $sessions,
            'user'          => $user
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
