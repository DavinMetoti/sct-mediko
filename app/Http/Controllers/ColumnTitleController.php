<?php

namespace App\Http\Controllers;

use App\Models\ColumnTitle;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ColumnTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, 'column-title.index']);

        if ($request->ajax()) {
            $data = ColumnTitle::query();
            return DataTables::of($data)
                ->addColumn('actions', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn"
                            data-id="' . htmlspecialchars($row->id, ENT_QUOTES, 'UTF-8') . '"
                            data-name="' . htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') . '"
                            data-column_1="' . htmlspecialchars($row->column_1, ENT_QUOTES, 'UTF-8') . '"
                            data-column_2="' . htmlspecialchars($row->column_2, ENT_QUOTES, 'UTF-8') . '"
                            data-column_3="' . htmlspecialchars($row->column_3, ENT_QUOTES, 'UTF-8') . '">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn"
                            data-id="' . htmlspecialchars($row->id, ENT_QUOTES, 'UTF-8') . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.column_title');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return response()->json([
            'success' => true,
            'message' => 'Endpoint not implemented for API',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'column_1' => 'required|string|max:255',
                'column_2' => 'required|string|max:255',
                'column_3' => 'required|string|max:255',
            ]);

            $record = ColumnTitle::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully',
                'data' => $record,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record',
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
            $record = ColumnTitle::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Record retrieved successfully',
                'data' => $record,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        return response()->json([
            'success' => true,
            'message' => 'Endpoint not implemented for API',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'column_1' => 'required|string|max:255',
                'column_2' => 'required|string|max:255',
                'column_3' => 'required|string|max:255',
            ]);

            $record = ColumnTitle::findOrFail($id);
            $record->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Record updated successfully',
                'data' => $record,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update record',
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
            $record = ColumnTitle::findOrFail($id);
            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
