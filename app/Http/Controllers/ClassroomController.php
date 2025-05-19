<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Classroom::select(['id', 'name', 'start_time', 'end_time', 'is_active']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active) {
                        return '<span class="badge bg-success">Aktif</span>';
                    } else {
                        return '<span class="badge bg-secondary">Tidak Aktif</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-success" onclick="openSessionModal('.$row->id.')"><i class="fas fa-plus"></i></button>
                        <button class="btn btn-sm btn-warning" onclick="openEditModal('.$row->id.')"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteClassroom('.$row->id.')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('quiz.content.layouts.classroom');
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
            $validatedData = $request->validate([
               'name' => 'required|string|max:255',
               'is_active' => 'required|boolean',
               'start_time' => 'required|date_format:Y-m-d H:i',
               'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            ]);

            Classroom::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Classroom created successfully',
                'data' => $validatedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create classroom',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $classroom = Classroom::with('sessions')->findOrFail($id);

            return response()->json([
                'success' => true,
                'classroom' => $classroom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get classroom',
                'error' => $e->getMessage(),
            ], 500);
        };
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
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'is_active' => 'required|boolean',
                'start_time' => 'required|date_format:Y-m-d H:i',
                'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            ]);

            $classroom = Classroom::findOrFail($id);
            $classroom->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Classroom updated successfully',
                'data' => $classroom
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update classroom',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            $classroom->delete();

            return response()->json([
                'success' => true,
                'message' => 'Classroom deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete classroom',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function attach(Request $request) {
        try {
            $validated = $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'session_ids' => 'array',
                'session_ids.*' => 'exists:quiz_sessions,id',
            ]);

            $classroom = Classroom::findOrFail($validated['classroom_id']);
            $classroom->sessions()->sync($validated['session_ids'] ?? []);

            return response()->json([
                'message' => 'Sesi berhasil dihubungkan ke classroom'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Classroom not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detach(Request $request) {
        try {
            $validated = $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'session_id' => 'required|exists:quiz_sessions,id',
            ]);

            $classroom = Classroom::findOrFail($validated['classroom_id']);
            $classroom->sessions()->detach($validated['session_id']);

            return response()->json([
                'message' => 'Sesi berhasil dihapus dari classroom'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
