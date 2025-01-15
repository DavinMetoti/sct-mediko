<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = request()->input('id');
        $person = User::with(['accessRole','userDetail'])->findOrFail($id);

        return view('public.profile', compact('person'));
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
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'username' => 'sometimes|string|unique:users,username,' . $id,
                'phone' => 'nullable|string|max:15',
                'gender' => 'sometimes|in:L,P',
                'address' => 'sometimes|string',
                'dob' => 'sometimes|date',
                'univ' => 'sometimes|string',
                'grade' => 'sometimes|string',
                'major' => 'sometimes|string',
                'study_programs' => 'sometimes|string',
            ]);

            // Update User data
            $user = User::findOrFail($id);
            $user->update(array_filter([
                'name' => $validatedData['name'] ?? $user->name,
                'email' => $validatedData['email'] ?? $user->email,
                'username' => $validatedData['username'] ?? $user->username,
                'phone' => $validatedData['phone'] ?? $user->phone,
            ]));

            // Update UserDetail data
            $userDetail = UserDetail::where('id_users', $id)->first();

            if ($userDetail) {
                $userDetail->update(array_filter([
                    'gender' => $validatedData['gender'] ?? $userDetail->gender,
                    'address' => $validatedData['address'] ?? $userDetail->address,
                    'dob' => $validatedData['dob'] ?? $userDetail->dob,
                    'univ' => $validatedData['univ'] ?? $userDetail->univ,
                    'grade' => $validatedData['grade'] ?? $userDetail->grade,
                    'major' => $validatedData['major'] ?? $userDetail->major,
                    'study_programs' => $validatedData['study_programs'] ?? $userDetail->study_programs,
                ]));
            } else {
                UserDetail::create([
                    'id_users' => $id,
                    'gender' => $validatedData['gender'] ?? null,
                    'address' => $validatedData['address'] ?? null,
                    'dob' => $validatedData['dob'] ?? null,
                    'univ' => $validatedData['univ'] ?? null,
                    'grade' => $validatedData['grade'] ?? null,
                ]);
            }

            return response()->json([
                'message' => 'User and UserDetail updated successfully',
                'success'=> true
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
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

            $userDetail = UserDetail::where('id_users', $id)->first();
            if ($userDetail) {
                $userDetail->delete();
            }

            $user->delete();

            return response()->json([
                'message' => 'User dan UserDetail berhasil dihapus.',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage (Update password).
     */
    public function updatePassword(Request $request, string $id)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'currentPassword' => 'required|string',
                'newPassword' => 'required|string|min:8',
                'confirmPassword' => 'required|string|min:8|same:newPassword',
            ]);

            // Ambil user berdasarkan ID
            $user = User::findOrFail($id);

            // Cek apakah password lama sesuai dengan yang ada di database
            if (!\Hash::check($validatedData['currentPassword'], $user->password)) {
                return response()->json([
                    'message' => 'Password lama yang Anda masukkan salah.',
                    'success' => false
                ], 400);
            }

            // Update password pengguna
            $user->update([
                'password' => \Hash::make($validatedData['newPassword']), // Enkripsi password baru
            ]);

            return response()->json([
                'message' => 'Password berhasil diganti.',
                'success' => true
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
