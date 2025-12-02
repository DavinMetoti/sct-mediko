<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedmasterySegmentation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MedMasterySegmantationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $segmentations = MedmasterySegmentation::with('creator', 'allowedUsers')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('medmastery.content.segmentation', compact('segmentations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::active()->orderBy('name')->get();
        return view('medmastery.content.segmentation-create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:medmastery_segmentation,name',
            'description' => 'nullable|string|max:1000',
            'color' => ['required', 'string', 'max:7', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'allowed_users' => 'nullable|array',
            'allowed_users.*' => 'exists:users,id',
        ], [
            'name.required' => 'Nama segmentasi wajib diisi.',
            'name.unique' => 'Nama segmentasi sudah ada, silakan gunakan nama lain.',
            'name.max' => 'Nama segmentasi maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'color.required' => 'Warna wajib dipilih.',
            'color.regex' => 'Format warna tidak valid.',
            'allowed_users.array' => 'Pengguna yang diizinkan harus berupa array.',
            'allowed_users.*.exists' => 'Pengguna yang dipilih tidak valid.',
        ]);

        try {
            // Buat segmentasi baru
            $segmentation = MedmasterySegmentation::create([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color,
                'created_by' => Auth::id(),
            ]);

            // Simpan allowed users jika ada
            if ($request->has('allowed_users') && is_array($request->allowed_users)) {
                $segmentation->allowedUsers()->attach($request->allowed_users);
            }

            return redirect()
                ->route('medmastery-segmentation.index')
                ->with('success', 'Segmentasi berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Logic to display a specific segmentation
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $segmentation = MedmasterySegmentation::findOrFail($id);
            $users = User::active()->orderBy('name')->get();
            return view('medmastery.content.segmentation-edit', compact('segmentation', 'users'));
        } catch (\Exception $e) {
            return redirect()
                ->route('medmastery-segmentation.index')
                ->with('error', 'Bidang tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:medmastery_segmentation,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'color' => ['required', 'string', 'max:7', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'allowed_users' => 'nullable|array',
            'allowed_users.*' => 'exists:users,id',
        ], [
            'name.required' => 'Nama bidang wajib diisi.',
            'name.unique' => 'Nama bidang sudah ada, silakan gunakan nama lain.',
            'name.max' => 'Nama bidang maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'color.required' => 'Warna wajib dipilih.',
            'color.regex' => 'Format warna tidak valid.',
            'allowed_users.array' => 'Pengguna yang diizinkan harus berupa array.',
            'allowed_users.*.exists' => 'Pengguna yang dipilih tidak valid.',
        ]);

        try {
            $segmentation = MedmasterySegmentation::findOrFail($id);
            
            $segmentation->update([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color,
            ]);

            // Update allowed users
            if ($request->has('allowed_users') && is_array($request->allowed_users)) {
                $segmentation->allowedUsers()->sync($request->allowed_users);
            } else {
                $segmentation->allowedUsers()->detach();
            }

            return redirect()
                ->route('medmastery-segmentation.index')
                ->with('success', 'Bidang berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $segmentation = MedmasterySegmentation::findOrFail($id);
            
            // Check if segmentation has categories
            if ($segmentation->categories()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Segmentasi tidak dapat dihapus karena masih digunakan oleh kategori.');
            }
            
            $segmentation->delete();
            
            return redirect()
                ->route('medmastery-segmentation.index')
                ->with('success', 'Segmentasi berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
