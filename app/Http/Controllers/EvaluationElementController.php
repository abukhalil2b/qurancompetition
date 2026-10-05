<?php

namespace App\Http\Controllers;

use App\Models\EvaluationElement;
use Illuminate\Http\Request;

class EvaluationElementController extends Controller
{
    public function index($id = 1)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = isset($levels[$id]) ? $id : 1;

        $level = $levels[$levelId];

        $elements = EvaluationElement::where('level', $levelId)->get();

        return view('evaluation_element.index', compact(
            'elements',
            'level',
            'levelId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level_id' => 'required|in:1,2',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        EvaluationElement::create([
            'title' => $request->title,
            'level' => $request->level_id,
            'max_score' => $request->max_score,
        ]);

        return back()->with('success', 'تمت إضافة عنصر التقييم بنجاح');
    }

    public function edit(EvaluationElement $evaluationElement)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $level = $levels[$evaluationElement->level] ?? 'غير محدد';

        return view('evaluation_element.edit', compact(
            'evaluationElement',
            'level'
        ));
    }

    public function update(Request $request, EvaluationElement $evaluationElement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        $evaluationElement->update([
            'title' => $request->title,
            'max_score' => $request->max_score,
        ]);

        return redirect()
            ->route('evaluation_element.index', $evaluationElement->level)
            ->with('success', 'تم تحديث عنصر التقييم بنجاح');
    }
}
