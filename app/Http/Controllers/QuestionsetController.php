<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use Illuminate\Http\Request;

class QuestionsetController extends Controller
{
    public function index()
    {
        $questionsets = Questionset::all();
        return view('questionset.index', compact('questionsets'));
    }

    public function create()
    {
        return view('questionset.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'selected' => 'boolean',
        ]);

        Questionset::create($request->all());

        return redirect()->route('questionset.index')->with('success', 'تم إنشاء مجموعة الأسئلة بنجاح');
    }

    public function show(Questionset $questionset)
    {
        $questions = $questionset->questions;
        return view('questionset.show', compact('questionset', 'questions'));
    }

    public function edit(Questionset $questionset)
    {
        return view('questionset.edit', compact('questionset'));
    }

    public function update(Request $request, Questionset $questionset)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'selected' => 'boolean',
        ]);

        $questionset->update($request->all());

        return redirect()->route('questionset.index')->with('success', 'تم تحديث مجموعة الأسئلة بنجاح');
    }

    public function destroy(Questionset $questionset)
    {
        $questionset->delete();
        return back()->with('success', 'تم حذف مجموعة الأسئلة');
    }
}
