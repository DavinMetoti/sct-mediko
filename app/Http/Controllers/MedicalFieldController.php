<?php

namespace App\Http\Controllers;

use App\Models\MedicalField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, 'medical-field.index']);

        $medicalFields = MedicalField::all();

        if ($request->ajax()) {
            try {
                return response()->json([
                    'success' => true,
                    'data' => $medicalFields,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch medical fields: ' . $e->getMessage(),
                ], 500);
            }
        }

        return view('admin.medical_field');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $medicalField = new MedicalField();
            $medicalField->name = $request->name;
            $medicalField->save();

            return response()->json([
                'success' => true,
                'message' => 'Medical field successfully added.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add medical field: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $medicalField = MedicalField::findOrFail($id);
            $medicalField->name = $request->name;
            $medicalField->save();

            return response()->json([
                'success' => true,
                'message' => 'Medical field successfully updated.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update medical field: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $medicalField = MedicalField::findOrFail($id);
            $medicalField->delete();

            return response()->json([
                'success' => true,
                'message' => 'Medical field successfully deleted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete medical field: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the list of medical fields.
     */
    public function getMedicalFields()
    {
        try {
            $medical_fields = MedicalField::all();

            return datatables()->of($medical_fields)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary edit-medical-field" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') . '">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-medical-field" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch medical fields: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getMedicalFieldByName(Request $request)
    {
        try {
            // Ambil parameter pencarian dari request
            $search = $request->input('search');

            // Cari pertanyaan yang sesuai dengan query pencarian
            $questions = MedicalField::where('name', 'like', "%{$search}%")
                ->limit(10)
                ->get();

            // Periksa apakah data ditemukan
            if ($questions->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'message' => 'Data ditemukan',
                'data' => $questions,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
