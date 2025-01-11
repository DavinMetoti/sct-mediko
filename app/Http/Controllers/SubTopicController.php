<?php

namespace App\Http\Controllers;

use App\Models\SubTopic;
use App\Models\User;
use Illuminate\Http\Request;

class SubTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class, 'sub-topic.index']);

        return view('admin.sub_topic');
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
                'description' => 'nullable|string',
                'path' => 'required|string|max:255',
                'id_header_sub_topic' => 'required|integer|exists:header_sub_topics,id',
            ]);

            $subTopic = SubTopic::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Sub topik berhasil disimpan!',
                'data' => $subTopic,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan sub topik',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $subTopic = SubTopic::findOrFail($id);

            return response()->json([
                'sub_topic' => $subTopic,
                'message' => 'Sub topic found successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Sub topic not found',
                'message' => $e->getMessage()
            ], 404);
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
            // Find the sub-topic by its ID
            $subTopic = SubTopic::findOrFail($id);

            // Validate incoming data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'path' => 'nullable|string',
            ]);

            // Check if path is empty or null, if so, retain the old value
            if (empty($validatedData['path'])) {
                $validatedData['path'] = $subTopic->path;
            }

            // Update the sub-topic with the new data
            $subTopic->update($validatedData);

            return response()->json([
                'message' => 'Sub topic updated successfully!',
                'sub_topic' => $subTopic,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating sub-topic',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $subTopic = SubTopic::findOrFail($id);
            $subTopic->delete();

            return response()->json([
                'message' => 'SubTopic berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'SubTopic tidak ditemukan atau terjadi kesalahan.'
            ], 500);
        }
    }


    public function getSubTopicTable(Request $request)
    {
        if ($request->ajax()) {
            $subTopics = SubTopic::where('id_header_sub_topic', $request->id)->get();

            return datatables()->of($subTopics)
                ->addIndexColumn()
                ->addColumn('preview', function ($row) {
                    return '
                        <a href="'.$row->path.'" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-up-right-from-square me-2"></i>Lihat Dokumen
                        </a>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-warning btn-edit-subtopic">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete-subtopic">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['preview', 'actions']) // Pastikan kolom preview juga di-parse sebagai raw HTML
                ->make(true);
        }
    }

}
