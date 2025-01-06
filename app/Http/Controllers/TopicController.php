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
        //
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
        //
    }

    public function getTopicTable(Request $request)
    {
        if ($request->ajax()) {
            $topics = HeaderSubTopic::all();

            return datatables()->of($topics)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    // Menambahkan kolom aksi (edit/hapus)
                    return '
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-primary" id="btn-show-topic"><i class="fas fa-eye"></i></button>
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-warning" id="btn-edit-topic"><i class="fas fa-edit"></i></button>
                        <button data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-delete" id="btn-delete-topic"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions']) // Mengizinkan kolom actions menggunakan HTML
                ->make(true);
        }
    }
}
