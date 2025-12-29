<?php

namespace App\Http\Controllers;

use App\Models\EvaluationElement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationElementController extends Controller
{

    public function index($id = 1)
    {


        $levels = [1 => 'حفظ', 2 => 'حفظ وتفسير'];

        $level_id = $levels[$id] ? $id : 1;

        // get the level name
        $level = $levels[$level_id];

        $elements = EvaluationElement::where('level', $level)->get();

        return view('evaluation_element.index', compact('elements', 'level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:حفظ,حفظ وتفسير',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        EvaluationElement::create($request->only('title', 'level', 'max_score'));

        return back()->with('success', 'تمت إضافة عنصر التقييم بنجاح');
    }

     public function edit(EvaluationElement $evaluationElement)
    {
        return view('evaluation_element.edit', compact('evaluationElement'));
    }


    public function update(Request $request, EvaluationElement $evaluationElement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'max_score' => 'required|integer|min:1|max:20',
        ]);

        $evaluationElement->update($request->only('title', 'max_score'));

        
        return back()
            ->with('success', 'تم تحديث عنصر التقييم بنجاح');
    }


}
