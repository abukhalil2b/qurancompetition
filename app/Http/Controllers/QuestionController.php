<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function index(Questionset $questionset)
    {
        // Get all questions belonging to this questionset
        $questions = $questionset->questions()->get();

        return view('question.index', compact('questionset', 'questions'));
    }

    public function create(Questionset $questionset)
    {
        return view('question.create', compact('questionset'));
    }

    public function store(Request $request, Questionset $questionset)
    {
        $request->validate([
            'content' => 'required|string',
            'level' => 'required|in:easy,medium,hard',
            'branch' => 'required|string|max:255',
        ]);

        $questionset->questions()->create($request->all());

        return redirect()->route('questionset.show', $questionset)->with('success', 'تم إضافة السؤال بنجاح');
    }

    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => 'required|string',
            'level' => 'required|in:easy,medium,hard',
            'branch' => 'required|string|max:255',
        ]);

        $question->update($request->all());

        return redirect()->route('questionset.show', $question->questionset_id)->with('success', 'تم تحديث السؤال بنجاح');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'تم حذف السؤال');
    }
}
