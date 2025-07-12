<?php

namespace App\Http\Controllers;

use App\Models\MedmasteryCategory;
use App\Models\MedmasterySegmentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MedMasteryCategoryController extends Controller
{
    /**
     * Check if user has admin access
     */
    private function checkAdminAccess()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }
        
        if (!$user->accessRole || $user->accessRole->access !== 'private') {
            abort(403, 'Halaman ini hanya dapat diakses oleh administrator. Siswa dapat mengakses konten melalui menu Medmaster Deck di /medmastery.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAdminAccess();
        
        $categories = MedmasteryCategory::with(['segmentation', 'creator'])->get();
        return view('medmastery.content.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdminAccess();
        
        $segmentations = MedmasterySegmentation::all();
        return view('medmastery.content.category-create', compact('segmentations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();
        $request->validate([
            'medmastery_segmentation_id' => 'required|exists:medmastery_segmentation,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('medmastery_category')->where(function ($query) use ($request) {
                    return $query->where('medmastery_segmentation_id', $request->medmastery_segmentation_id);
                })
            ],
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|image|mimes:png|max:2048',
        ], [
            'medmastery_segmentation_id.required' => 'Bidang kedokteran harus dipilih.',
            'medmastery_segmentation_id.exists' => 'Bidang kedokteran yang dipilih tidak valid.',
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada dalam bidang yang sama.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'icon.required' => 'Gambar ikon kategori harus diupload.',
            'icon.image' => 'File harus berupa gambar.',
            'icon.mimes' => 'Gambar harus berformat PNG.',
            'icon.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            // Convert uploaded image to base64
            $iconBase64 = null;
            if ($request->hasFile('icon')) {
                $image = $request->file('icon');
                $imageData = file_get_contents($image->getRealPath());
                $iconBase64 = 'data:image/png;base64,' . base64_encode($imageData);
            }

            MedmasteryCategory::create([
                'medmastery_segmentation_id' => $request->medmastery_segmentation_id,
                'name' => $request->name,
                'description' => $request->description,
                'icon' => $iconBase64,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('medmastery-category.index')
                ->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kategori.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = MedmasteryCategory::with(['segmentation', 'creator'])
            ->withCount(['questions', 'activeQuestions'])
            ->findOrFail($id);
        
        $user = Auth::user();
        $userRole = $user ? $user->accessRole : null;
        
        return view('medmastery.content.category-show', compact('category', 'user', 'userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkAdminAccess();
        
        try {
            $category = MedmasteryCategory::findOrFail($id);
            $segmentations = MedmasterySegmentation::all();
            return view('medmastery.content.category-edit', compact('category', 'segmentations'));
        } catch (\Exception $e) {
            return redirect()->route('medmastery-category.index')
                ->with('error', 'Kategori tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAdminAccess();
        $request->validate([
            'medmastery_segmentation_id' => 'required|exists:medmastery_segmentation,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('medmastery_category')->where(function ($query) use ($request) {
                    return $query->where('medmastery_segmentation_id', $request->medmastery_segmentation_id);
                })->ignore($id)
            ],
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|image|mimes:png|max:2048',
        ], [
            'medmastery_segmentation_id.required' => 'Bidang kedokteran harus dipilih.',
            'medmastery_segmentation_id.exists' => 'Bidang kedokteran yang dipilih tidak valid.',
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada dalam bidang yang sama.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'icon.image' => 'File harus berupa gambar.',
            'icon.mimes' => 'Gambar harus berformat PNG.',
            'icon.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $category = MedmasteryCategory::findOrFail($id);
            
            $updateData = [
                'medmastery_segmentation_id' => $request->medmastery_segmentation_id,
                'name' => $request->name,
                'description' => $request->description,
            ];

            // Handle icon upload if new image is provided
            if ($request->hasFile('icon')) {
                $image = $request->file('icon');
                $imageData = file_get_contents($image->getRealPath());
                $iconBase64 = 'data:image/png;base64,' . base64_encode($imageData);
                $updateData['icon'] = $iconBase64;
            }
            
            $category->update($updateData);

            return redirect()->route('medmastery-category.index')
                ->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kategori.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAdminAccess();
        
        try {
            $category = MedmasteryCategory::findOrFail($id);
            
            // Check if category has any related data before deletion
            // Add relationship checks here if needed
            
            $category->delete();
            
            return redirect()->route('medmastery-category.index')
                ->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('medmastery-category.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
