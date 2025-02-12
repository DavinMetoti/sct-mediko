<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Question;
use App\Models\QuestionDetail;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('accessRole')->findOrFail(auth()->id());

        if (optional($user->accessRole)->access === 'private') {
            $this->authorize('viewAny', [User::class, 'dashboard.index']);

            $question_total = QuestionDetail::count();
            $questionActive_total = Question::where('status', 'active')->count();
            $student_total = User::whereHas('accessRole', function ($query) {
                $query->where('access', 'public');
            })->count();
            $student = User::whereHas('accessRole', function ($query) {
                $query->where('access', 'public');
            })->get();

            $package = Package::with('users')->get();
            return view('admin.dashboard', compact('question_total', 'student_total', 'questionActive_total', 'package', 'student'));
        } else {
            return redirect()->route('student.index');
        }
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
