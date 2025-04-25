<?php

namespace App\Http\Controllers;

use App\Models\UserFolder;
use App\Models\UserLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = UserLibrary::with([
            'session' => function ($query) {
                $query->withCount('questions');
            },
            'folder'
        ]);

        if ($filter === 'like') {
            $query->whereNull('folder_id');
        }

        $libraries = $query->get();

        $groupedLibraries = $libraries->groupBy(fn($library) => $library->folder->folder_name ?? 'Suka');

        $groupedLibraries = $groupedLibraries->sortKeysUsing(fn($a, $b) =>
            $a === 'Suka' ? -1 : ($b === 'Suka' ? 1 : strcmp($a, $b))
        );

        return view('quiz.content.layouts.library', [
            "groupedLibraries" => $groupedLibraries,
            "folderId" => false,
            "id"=> null
        ]);
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
            // Validasi input
            $request->validate([
                'quiz_session_id' => 'required|exists:quiz_sessions,id',
                'folder_id' => 'nullable|exists:user_folders,id',
            ]);

            // Cek apakah user_id dan quiz_session_id sudah ada
            $existingLibrary = UserLibrary::where('user_id', Auth::id())
                                        ->where('quiz_session_id', $request->quiz_session_id)
                                        ->first();

            if ($existingLibrary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dengan user_id dan quiz_session_id yang sama sudah ada',
                ], 400); // Kode status 400 untuk Bad Request
            }

            // Simpan data baru jika tidak ada duplikat
            $userLibrary = UserLibrary::create([
                'user_id' => Auth::id(),
                'quiz_session_id' => $request->quiz_session_id,
                'folder_id' => $request->folder_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $userLibrary
            ], 201); // Kode status 201 untuk Created
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500); // Kode status 500 untuk Internal Server Error
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $collections = UserFolder::findOrFail($id);

        $libraries = UserLibrary::with([
            'session' => function ($query) {
                $query->withCount('questions');
            },
            'folder'
        ])->where('folder_id', $id)->get();

        $groupedLibraries = $libraries->isEmpty() ? collect() :
            $libraries->groupBy(fn($library) => $library->folder->folder_name ?? 'Suka')
                      ->sortKeysUsing(fn($a, $b) => $a === 'Suka' ? -1 : ($b === 'Suka' ? 1 : strcmp($a, $b)));

        return view('quiz.content.layouts.library', [
            'folder' => $collections,
            'groupedLibraries' => $groupedLibraries,
            "folderId" => true,
            'id'=>$id
        ]);
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
        try {
            // Cari data berdasarkan ID
            $userLibrary = UserLibrary::findOrFail($id);

            $userLibrary->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete_folder(Request $request)
    {
        try {
            $folder = UserFolder::findOrFail($request->id);
            $folder->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Folder berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus folder.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
