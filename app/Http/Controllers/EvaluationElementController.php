<?php

namespace App\Http\Controllers;

use App\Models\EvaluationElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationElementController extends Controller
{
    /**
     * Display a listing of the evaluation elements.
     *
     * Retrieves all parent_id elements (headers) and loads their children for display.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all top-level elements (headers where 'parent_id' is null)
        // and eager load their child elements, ordered by 'order'.
        $elements = EvaluationElement::whereNull('parent_id')
            ->with('childElements')
            ->orderBy('order')
            ->get();

        // Pass the data to the view
        return view('evaluation_element.index', compact('elements'));
    }

    /**
     * Show the form for creating a new evaluation element.
     *
     * This is typically combined with the 'store' logic in a single form.
     * I'm providing a minimal implementation that retrieves data needed for the form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all existing headers (elements with parent_id = null) to populate
        // a dropdown so the user can select a parent_id for a new actual element.
        $headers = EvaluationElement::whereNull('parent_id')
            ->orderBy('order')
            ->get(['id', 'title']);

        // Define the available branches for the form
        $branches = ['تفسير وحفظ', 'فقط حفظ'];

        // You'll need a view named 'evaluation_element.create'
        return view('evaluation_element.create', compact('headers', 'branches'));
    }

    // Assuming this is inside EvaluationElementController


    public function store(Request $request)
    {
        // Define the base rules
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                // Enforce unique title within the same parent scope.
                'unique:evaluation_elements,title,NULL,id,parent_id,' . ($request->input('parent_id') ?? 'NULL')
            ],
            'parent_id' => 'nullable|exists:evaluation_elements,id',
            'order' => 'nullable|integer|min:0',
        ];

        // --- Conditional Validation Logic ---
        if ($request->filled('parent_id')) {
            // Child Element Rules: Max score required (must be > 0), Branch not needed.
            $rules['max_score'] = 'required|integer|min:1';
            $rules['branch'] = 'nullable';
        } else {
            // Header Element Rules: Branch required (from defined options), Max score not needed.
            $rules['branch'] = 'required|in:تفسير وحفظ,فقط حفظ';
            $rules['max_score'] = 'nullable';
        }

        $validatedData = $request->validate($rules);

        // --- Data Cleaning for Database Integrity ---
        if ($request->filled('parent_id')) {
            // Child Element: Ensure branch is null
            $validatedData['branch'] = null;
        } else {
            // Header Element: Ensure max_score is null
            $validatedData['max_score'] = null;
        }

        // Create the Element
        EvaluationElement::create($validatedData);

        // Redirect
        return redirect()->route('evaluation_element.index')
            ->with('success', 'تم إضافة عنصر التقييم بنجاح.');
    }

    public function evaluation()
    {
        return view('evaluation');
    }

    public function edit(EvaluationElement $evaluationElement)
    {
        // Fetch all top-level elements to be used as potential parents in the dropdown.
        // We exclude the current element itself from the list of potential parents to prevent loops.
        $parentElements = EvaluationElement::whereNull('parent_id')
            ->where('id', '!=', $evaluationElement->id)
            ->orderBy('order')
            ->pluck('title', 'id');

        return view('evaluation_element.edit', compact('evaluationElement', 'parentElements'));
    }

    /**
     * Update the specified evaluation element in storage.
     */
    public function update(Request $request, EvaluationElement $evaluationElement)
    {
        // Determine the validation rules based on whether the element has a parent_id or not.
        $rules = [
            'title'      => 'required|string|max:255',
            'order'      => 'required|integer|min:1',
            'parent_id'     => 'nullable|exists:evaluation_elements,id',
        ];

        // If 'parent_id' is null (it's a top-level element), 'branch' is required.
        // If 'parent_id' is not null (it's a child element), 'max_score' is required.
        if (empty($request->input('parent_id'))) {
            $rules['branch'] = 'required|string|max:255';
            $rules['max_score'] = 'nullable'; // Max score is irrelevant for headers
        } else {
            $rules['branch'] = 'nullable';
            $rules['max_score'] = 'required|integer|min:1';
        }

        // Add unique check for 'title' within the same parent_id/scope
        $rules['title'] .= '|unique:evaluation_elements,title,' . $evaluationElement->id . ',id,parent_id,' . ($request->input('parent_id') ?? 'NULL');


        $validated = $request->validate($rules);

        // Ensure 'branch' or 'max_score' is explicitly null if not set by validation
        if (empty($validated['parent_id'])) {
            $validated['max_score'] = null;
        } else {
            $validated['branch'] = null;
        }

        $evaluationElement->update($validated);

        return redirect()->route('evaluation_element.index')->with('success', 'تم تحديث عنصر التقييم بنجاح.');
    }

    // Inside EvaluationElementController::destroy

    public function destroy(EvaluationElement $evaluationElement)
    {
        // Check if any JudgeEvaluations exist for this element
        if ($evaluationElement->judgeEvaluations()->exists()) {
            return redirect()->route('evaluation_element.index')->with('error', 'لا يمكن حذف عنصر التقييم هذا لوجود تقييمات مرتبطة به في سجلات المحكمين.');
        }

        // If it's a parent_id, ensure children are deleted or handled (if they exist)
        if (is_null($evaluationElement->parent_id)) {
            // If you still need cascade delete for children:
            EvaluationElement::where('parent_id', $evaluationElement->id)->delete();
        }

        $evaluationElement->delete();

        return redirect()->route('evaluation_element.index')->with('success', 'تم حذف عنصر التقييم بنجاح.');
    }
}
