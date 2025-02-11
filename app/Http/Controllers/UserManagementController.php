<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'user-management.index']);

        return view('admin.user_management');
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
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
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
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'username' => [
                    'sometimes',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username')->ignore($id, 'id'), // Pastikan 'id' adalah nama primary key
                ],
                'id_access_role' => 'sometimes|integer|exists:access_roles,id',
            ]);

            // Periksa apakah ada perubahan pada data
            $changes = array_diff_assoc($validatedData, $user->only(array_keys($validatedData)));

            if (empty($changes)) {
                return response()->json([
                    'message' => 'Tidak ada perubahan data.',
                    'data' => $user,
                ], 200);
            }

            // Simpan perubahan jika ada
            foreach ($changes as $field => $value) {
                $user->{$field} = $value;
            }

            $user->save();

            return response()->json([
                'message' => 'Data berhasil diperbarui.',
                'data' => $user,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data.',
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

            $user = User::findOrFail($id);
            $this->authorize('delete', $user);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function getPrivateUserData()
    {
        try {
            $users = User::with('accessRole')->whereHas('accessRole', function ($query) {
                $query->where('access', 'private');
            })->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '<button class="btn btn-sm btn-primary edit-button" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger delete-button" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
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
