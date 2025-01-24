<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

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

        $question = Question::with([
            'questionDetail.medicalField',
            'questionDetail.subTopic',
            'questionDetail.questionType'
        ])
        ->where('id', $idQuestion)
        ->first();
        $user = User::with('userDetail')->findOrFail(auth()->id());

        return view('public.tryout', compact(['question','user']));
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
