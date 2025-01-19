<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $packages = Package::all();

            return DataTables::of($packages)
                ->addColumn('action', function ($package) {
                    return '
                    <button class="btn btn-md edit-package" data-id="' . $package->id . '">
                        <i class="fas text-primary fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-md delete-package" data-id="' . $package->id . '">
                        <i class="fas text-danger fa-trash"></i>
                    </button>
                    <button class="btn btn-md question-package" data-id="' . $package->id . '">
                        <i class="fas text-success fa-question-circle"></i>
                    </button>
                ';
             })
                ->make(true);
        }

        return view('admin.package');
    }

    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expired_date' => 'nullable|date',
        ]);


        $package = new Package();
        $package->name = $validated['name'];
        $package->price = $validated['price'];
        $package->description = $validated['description'];
        $package->expires_at = $validated['expired_date'] ?? null;


        $package->save();


        return response()->json([
            'success' => true,
            'message' => 'Package created successfully.',
            'data' => $package
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $package = Package::findOrFail($id);

        return response()->json([
            'success' => true,
            'package' => $package
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = Package::findOrFail($id);

        $questions = $package->questions;

        return DataTables::of($questions)
            ->addIndexColumn()
            ->addColumn('action', function ($question) use ($package) {
                return '<button type="button" class="btn delete-question" data-id="' . $question->id . '"
                        data-package-id="' . $package->id . '"><i class="fas text-danger fa-trash"></i></button>';

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'expires_at' => 'sometimes|date',
            'questions' => 'sometimes|array',
        ]);

        $package = Package::findOrFail($id);

        collect($validated)->each(function ($value, $key) use ($package) {
            if (in_array($key, ['name', 'price', 'description', 'expires_at'])) {
                $package->$key = $value;
            }
        });

        $package->save();

        if (isset($validated['questions'])) {
            $package->questions()->sync($validated['questions']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully.',
            'package' => $package
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::findOrFail($id);

        $package->questions()->detach();

        $package->delete();

        return response()->json([
            'message' => 'Package deleted successfully.',
            'status' => 'success'
        ]);
    }

    public function searchQuestions(Request $request)
    {
        $search = $request->input('search');

        $questions = Question::query()
            ->where('question', 'like', '%' . $search . '%')
            ->where('is_public', 0)
            ->limit(10)
            ->get();


        return response()->json($questions);
    }


    public function getSelectedQuestions($id)
    {
        $package = Package::findOrFail($id);

        $selectedQuestions = $package->questions()->with('question')
            ->pluck('question_id')
            ->toArray();

        $questionsData = Question::whereIn('id', $selectedQuestions)
            ->get(['id', 'question'])
            ->map(function ($item) {
                return ['id' => $item->id, 'text' => $item->question];
            });

        return response()->json([
            'selectedQuestions' => $questionsData
        ]);
    }



    /**
     * Attach a single question to a package.
     */
    public function attachQuestion(Request $request, string $packageId)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
        ]);

        $package = Package::findOrFail($packageId);
        $package->questions()->attach($validated['question_id']);

        return redirect()->route('packages.index')->with('success', 'Question attached to package.');
    }

    /**
     * Detach a single question from a package.
     */
    public function detachQuestion(Request $request, string $packageId)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
        ]);

        $package = Package::findOrFail($packageId);
        $package->questions()->detach($validated['question_id']);

        return redirect()->route('packages.index')->with('success', 'Question detached from package.');
    }

    /**
     * Update a question's pivot data.
     */
    public function updateQuestionPivot(Request $request, string $packageId, string $questionId)
    {
        $validated = $request->validate([
            'order' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);

        $package = Package::findOrFail($packageId);

        $package->questions()->updateExistingPivot($questionId, $validated);

        return redirect()->route('packages.index')->with('success', 'Question pivot data updated.');
    }

    public function destroyQuestion($packageId, $questionId)
    {
        $package = Package::findOrFail($packageId);

        $package->questions()->detach($questionId);

        return response()->json([
            'success' => true,
            'message' => 'Question removed from the package successfully.',
        ]);
    }

}
