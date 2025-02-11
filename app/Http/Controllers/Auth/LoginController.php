<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Login process.
     */
    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            $user = User::with('accessRole')->where('username', $validate['username'])->firstOrFail();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Username tidak ditemukan.'
            ]);
        }

        if (!Hash::check($validate['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah.'
            ]);
        }

        if ($user->is_actived == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda tidak aktif.'
            ]);
        }

        Auth::login($user);

        $sessionToken = Str::uuid()->toString();

        $user->session_token = $sessionToken;
        $user->save();

        session(['session_token' => $sessionToken]);

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'redirect' => $user->accessRole->access == "private" ? route('dashboard.index') : route('student.index')
        ]);
    }

    /**
     * Display a listing of the register.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store register data.
     */
    public function registerStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'id_access_role' => 'required|integer',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'id_access_role' => $validatedData['id_access_role'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil. Silakan login.',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan validasi.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
            'redirect' => route('login')
        ]);
    }

}
