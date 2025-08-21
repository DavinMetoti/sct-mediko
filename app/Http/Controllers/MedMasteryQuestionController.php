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

        // Show questions based on visibility
        $visibility = $request->input('visibility', 'my');
        
        if ($visibility === 'my') {
            // Only show questions created by the current user
            $query->where('creator_id', Auth::id());
        } elseif ($visibility === 'public') {
            // Show all public questions
            $query->where('is_public', true);
        } elseif ($visibility === 'all') {
            // Show user's own questions AND public questions
            $query->where(function($q) {
                $q->where('creator_id', Auth::id())
                  ->orWhere('is_public', true);
            });
        }

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
        
        // Show all categories to all users
        $categories = MedmasteryCategory::with('segmentation')
            ->orderBy('name')
            ->get();

        return view('medmastery.content.question', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show all categories to all users
        $categories = MedmasteryCategory::with('segmentation')
            ->orderBy('name')
            ->get();
            
        return view('medmastery.content.question-create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medmastery_category_id' => 'required|exists:medmastery_category,id',
            'question_text' => 'required|string',
            'explanation' => 'required|string',
            'explanation_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'is_public' => 'nullable|boolean',
        ], [
            'medmastery_category_id.required' => 'Kategori harus dipilih',
            'medmastery_category_id.exists' => 'Kategori yang dipilih tidak valid',
            'question_text.required' => 'Pertanyaan tidak boleh kosong',
            'explanation.required' => 'Penjelasan tidak boleh kosong',
            'explanation_pdf.file' => 'File harus berupa dokumen PDF',
            'explanation_pdf.mimes' => 'File harus berformat PDF',
            'explanation_pdf.max' => 'Ukuran file maksimal 10MB'
        ]);

        // Allow users to use any available category
        $category = MedmasteryCategory::where('id', $request->medmastery_category_id)
            ->first();

        if (!$category) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kategori yang dipilih tidak valid.');
        }

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
            'is_public' => $request->is_public == '1' ? true : false,
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
        
        // Check if user can view this question (own question or public question)
        if ($question->creator_id !== Auth::id() && !$question->is_public) {
            return redirect()
                ->route('medmastery-question.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pertanyaan ini.');
        }
        
        return view('medmastery.content.question-show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = MedMasteryQuestion::with(['category.segmentation'])->findOrFail($id);
        
        // Check if user owns this question
        if ($question->creator_id !== Auth::id()) {
            return redirect()
                ->route('medmastery-question.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit pertanyaan ini.');
        }
        
        // Show all categories to all users
        $categories = MedmasteryCategory::with('segmentation')
            ->orderBy('name')
            ->get();
            
        return view('medmastery.content.question-edit', compact('question', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $question = MedMasteryQuestion::findOrFail($id);

        // Check if user owns this question
        if ($question->creator_id !== Auth::id()) {
            return redirect()
                ->route('medmastery-question.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit pertanyaan ini.');
        }

        $request->validate([
            'medmastery_category_id' => 'required|exists:medmastery_category,id',
            'question_text' => 'required|string',
            'explanation' => 'required|string',
            'explanation_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'is_public' => 'nullable|boolean',
        ], [
            'medmastery_category_id.required' => 'Kategori harus dipilih',
            'medmastery_category_id.exists' => 'Kategori yang dipilih tidak valid',
            'question_text.required' => 'Pertanyaan tidak boleh kosong',
            'explanation.required' => 'Penjelasan tidak boleh kosong',
            'explanation_pdf.file' => 'File harus berupa dokumen PDF',
            'explanation_pdf.mimes' => 'File harus berformat PDF',
            'explanation_pdf.max' => 'Ukuran file maksimal 10MB'
        ]);

        // Allow users to use any available category
        $category = MedmasteryCategory::where('id', $request->medmastery_category_id)
            ->first();

        if (!$category) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kategori yang dipilih tidak valid.');
        }

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
            'is_public' => $request->is_public == '1' ? true : false,
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

        // Check if user owns this question
        if ($question->creator_id !== Auth::id()) {
            return redirect()
                ->route('medmastery-question.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus pertanyaan ini.');
        }

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
