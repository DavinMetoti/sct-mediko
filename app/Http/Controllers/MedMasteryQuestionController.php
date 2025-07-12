<?php

namespace App\Http\Controllers;

use App\Models\MedMasteryQuestion;
use App\Models\MedmasteryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MedMasteryQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MedMasteryQuestion::with(['category.segmentation', 'creator']);

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('medmastery_category_id', $request->category_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                  ->orWhere('explanation', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = MedmasteryCategory::with('segmentation')->orderBy('name')->get();

        return view('medmastery.content.question', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MedmasteryCategory::with('segmentation')->orderBy('name')->get();
        return view('medmastery.content.question-create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medmastery_category_id' => 'required|exists:medmastery_category,id',
            'question_text' => 'required|string|max:2000',
            'explanation' => 'required|string|max:5000',
            'explanation_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'medmastery_category_id.required' => 'Kategori harus dipilih',
            'medmastery_category_id.exists' => 'Kategori yang dipilih tidak valid',
            'question_text.required' => 'Pertanyaan tidak boleh kosong',
            'question_text.max' => 'Pertanyaan maksimal 2000 karakter',
            'explanation.required' => 'Penjelasan tidak boleh kosong',
            'explanation.max' => 'Penjelasan maksimal 5000 karakter',
            'explanation_pdf.file' => 'File harus berupa dokumen PDF',
            'explanation_pdf.mimes' => 'File harus berformat PDF',
            'explanation_pdf.max' => 'Ukuran file maksimal 10MB'
        ]);

        $pdfPath = null;

        // Handle PDF upload
        if ($request->hasFile('explanation_pdf')) {
            $file = $request->file('explanation_pdf');
            $filename = 'explanation_' . Str::random(20) . '_' . time() . '.pdf';
            $pdfPath = $file->storeAs('medmastery/explanations', $filename, 'public');
        }

        $question = MedMasteryQuestion::create([
            'medmastery_category_id' => $request->medmastery_category_id,
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
            'explanation_pdf_path' => $pdfPath,
            'creator_id' => Auth::id(),
            'is_active' => $request->is_active == '1' ? true : false,
        ]);

        return redirect()
            ->route('medmastery-question.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = MedMasteryQuestion::with(['category.segmentation', 'creator'])->findOrFail($id);
        return view('medmastery.content.question-show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = MedMasteryQuestion::with(['category.segmentation'])->findOrFail($id);
        $categories = MedmasteryCategory::with('segmentation')->orderBy('name')->get();
        return view('medmastery.content.question-edit', compact('question', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $question = MedMasteryQuestion::findOrFail($id);

        $request->validate([
            'medmastery_category_id' => 'required|exists:medmastery_category,id',
            'question_text' => 'required|string|max:2000',
            'explanation' => 'required|string|max:5000',
            'explanation_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'medmastery_category_id.required' => 'Kategori harus dipilih',
            'medmastery_category_id.exists' => 'Kategori yang dipilih tidak valid',
            'question_text.required' => 'Pertanyaan tidak boleh kosong',
            'question_text.max' => 'Pertanyaan maksimal 2000 karakter',
            'explanation.required' => 'Penjelasan tidak boleh kosong',
            'explanation.max' => 'Penjelasan maksimal 5000 karakter',
            'explanation_pdf.file' => 'File harus berupa dokumen PDF',
            'explanation_pdf.mimes' => 'File harus berformat PDF',
            'explanation_pdf.max' => 'Ukuran file maksimal 10MB'
        ]);

        $pdfPath = $question->explanation_pdf_path;

        // Handle PDF upload
        if ($request->hasFile('explanation_pdf')) {
            // Delete old PDF if exists
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            $file = $request->file('explanation_pdf');
            $filename = 'explanation_' . Str::random(20) . '_' . time() . '.pdf';
            $pdfPath = $file->storeAs('medmastery/explanations', $filename, 'public');
        }

        $question->update([
            'medmastery_category_id' => $request->medmastery_category_id,
            'question_text' => $request->question_text,
            'explanation' => $request->explanation,
            'explanation_pdf_path' => $pdfPath,
            'is_active' => $request->is_active == '1' ? true : false,
        ]);

        return redirect()
            ->route('medmastery-question.index')
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = MedMasteryQuestion::findOrFail($id);

        // Delete PDF file if exists
        if ($question->explanation_pdf_path && Storage::disk('public')->exists($question->explanation_pdf_path)) {
            Storage::disk('public')->delete($question->explanation_pdf_path);
        }

        $question->delete();

        return redirect()
            ->route('medmastery-question.index')
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
