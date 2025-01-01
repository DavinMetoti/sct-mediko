<?php

namespace App\Http\Controllers;

use App\Models\AccessRole;
use App\Models\Menu;
use App\Models\PermissionAccessRole;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class AccessRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'access-role.index']);

        return view('admin.access_role');
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
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);

            AccessRole::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Hak akses berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(string $id)
    {
        try {
            $accessRole = AccessRole::find($id);

            if (!$accessRole) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            return response()->json($accessRole, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);

            $accessRole = AccessRole::findOrFail($id);
            $accessRole->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Hak akses berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating access role: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $accessRole = AccessRole::findOrFail($id);
            $accessRole->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Hak akses berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting access role: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function getAccessRoleData()
    {
        try {
            $accessRoles = AccessRole::all();

            return DataTables::of($accessRoles)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '<button class="btn btn-sm btn-primary edit-button" data-id="' . $row->id . '">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-button" data-id="' . $row->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary shield-button" data-id="' . $row->id . '">
                                <i class="fas fa-shield-alt"></i>
                            </button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error fetching access role data: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPrivateAccessRoleData()
    {
        try {
            $accessRoles = AccessRole::where('access', 'private')->get();

            return response()->json([
                'status' => 'success',
                'data' => $accessRoles,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching access role data: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPublicAccessRoleData()
    {
        try {
            $accessRoles = AccessRole::where('access', 'public')->select(['id', 'name']);
            return response()->json([
                'status' => 'success',
                'data' => $accessRoles,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching access role data: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function menuData()
    {
        try {
            $menus = Menu::all();

            return response()->json([
                'status' => 'success',
                'data' => $menus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function permissionData($id)
    {
        try {
            $permissions = PermissionAccessRole::where('access_role_id', $id)->get();

            return response()->json([
                'status' => 'success',
                'data' => $permissions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function saveOrUpdatePermission(Request $request)
    {
        try {
            $permissions = $request->input('permissions');

            if (!empty($permissions)) {
                $accessRoleId = $permissions[0]['id_access_role'];

                PermissionAccessRole::where('access_role_id', $accessRoleId)->delete();

                foreach ($permissions as $permissionData) {
                    PermissionAccessRole::create([
                        'access_role_id' => $permissionData['id_access_role'],
                        'route' => $permissionData['route'],
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Permissions successfully saved.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }




}
