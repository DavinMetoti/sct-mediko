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
        $filter = $request->query('filter', 'all'); // Ambil filter dari query string (default: 'all')

        $query = UserLibrary::with([
            'session' => function ($query) {
                $query->withCount('questions');
            },
            'folder'
        ]);

        if ($filter === 'like') {
            $query->whereNull('folder_id'); // Misalnya 'like' adalah folder kosong
        }

        $libraries = $query->get();

        $groupedLibraries = $libraries->groupBy(fn($library) => $library->folder->folder_name ?? 'Suka');

        $groupedLibraries = $groupedLibraries->sortKeysUsing(fn($a, $b) =>
            $a === 'Suka' ? -1 : ($b === 'Suka' ? 1 : strcmp($a, $b))
        );

        return view('quiz.content.layouts.library', [
            "groupedLibraries" => $groupedLibraries
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
            $request->validate([
                'quiz_session_id' => 'required|exists:quiz_sessions,id',
                'folder_id' => 'nullable|exists:user_folders,id',
            ]);

            $userLibrary = UserLibrary::create([
                'user_id' => Auth::id(),
                'quiz_session_id' => $request->quiz_session_id,
                'folder_id' => $request->folder_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $userLibrary
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
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
            'groupedLibraries' => $groupedLibraries
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
}
