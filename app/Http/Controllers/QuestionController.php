<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function index()
    {
        // Get all questions belonging to this questionset
        $questions = Question::all();

        return view('question.index', compact('questions'));
    }

    public function create(Questionset $questionset)
    {
        return view('question.create', compact('questionset'));
    }

    public function store(Request $request, Questionset $questionset)
    {
        $request->validate([
            'content' => 'required|string',
            'difficulties' => 'required',
        ]);

        $questionset->questions()->create([
            'content' => $request->content,
            'difficulties' => $request->difficulties,
        ]);

        return redirect()->route('questionset.show', $questionset)->with('success', 'تم إضافة السؤال بنجاح');
    }

    public function edit(Question $question)
    {
        $questionset = $question->questionset;

        return view('question.edit', compact('question', 'questionset'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'content' => 'required|string',
            'difficulties' => 'required|in:السهلة,المتوسطة,الصعبة',
        ]);

        $question->update([
            'content' => $request->content,
            'difficulties' => $request->difficulties,
        ]);

        return redirect()
            ->route('questionset.show', $question->questionset_id)
            ->with('success', 'تم تحديث السؤال بنجاح');
    }


    public function destroy(Question $question)
    {
        return 'معطل حاليا';
        $question->delete();
        return back()->with('success', 'تم حذف السؤال');
    }
}
