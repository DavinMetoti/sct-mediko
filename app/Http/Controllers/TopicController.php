<?php

namespace App\Http\Controllers;

use App\Models\HeaderSubTopic;
use Exception;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $headerSubTopic = new HeaderSubTopic();
            $headerSubTopic->name = $validatedData['name'];
            $headerSubTopic->save();

            return response()->json([
                'message' => 'SubTopic berhasil disimpan.',
                'data' => $headerSubTopic
            ], 201);

        } catch(Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan Topik.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $topic = HeaderSubTopic::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $topic,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
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
                'name' => 'required|string|max:255',
            ]);

            $topic = HeaderSubTopic::findOrFail($id);

            $topic->name = $validated['name'];
            $topic->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui.',
                'data' => $topic,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data.',
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $topic = HeaderSubTopic::findOrFail($id);


            $topic->delete();


            return response()->json([
                'success' => true,
                'message' => 'Topik berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus topik. ' . $e->getMessage()
            ], 500);
        }
    }


    public function getTopicTable(Request $request)
    {
        if ($request->ajax()) {
            $topics = HeaderSubTopic::all();

            return datatables()->of($topics)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {

                    return '
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-primary" id="btn-show-topic"><i class="fas fa-eye"></i></button>
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-warning" id="btn-edit-topic"><i class="fas fa-edit"></i></button>
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete" id="btn-delete-topic"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function getTopicData()
    {
        try {
            $topics = HeaderSubTopic::with('subTopics')->get();

            return response()->json([
                'message' => 'Topics fetched successfully!',
                'results' => $topics
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching topics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
