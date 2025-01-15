<?php

namespace App\Http\Controllers;

use App\Models\AccessRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;



class ListStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'list-students.index']);

        return view('admin.list_student');
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
        $users = User::with(['accessRole','userDetail'])
            ->where('id', $id)
            ->whereHas('accessRole', function ($query) {
                $query->where('access', 'public');
            })
            ->first();

        return response()->json($users);
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

    public function getPublicUserData()
    {
        try {
            $users = User::with('accessRole')->whereHas('accessRole', function ($query) {
                $query->where('access', 'public');
            })->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    // return '<button class="btn btn-sm btn-danger delete-button" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
                    return '<button class="btn btn-sm text-success package-button" data-id="'.$row->id.'"><i class="fas fa-book"></i></button>
                            <button class="btn btn-sm text-primary show-button" data-id="'.$row->id.'"><i class="fas fa-user"></i></button>
                            <button class="btn btn-sm text-danger delete-button" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
                })
                ->addColumn('access_role', function($row) {
                    return $row->accessRole->name;
                })
                ->rawColumns(['action'])
                ->make(true);

        } catch (\Exception $e) {
            // Error handling
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
