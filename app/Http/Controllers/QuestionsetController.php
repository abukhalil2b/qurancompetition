<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use Illuminate\Http\Request;

class QuestionsetController extends Controller
{
    public function index()
    {
        $questionsets = Questionset::withCount('questions')->get();

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
        ]);

        Questionset::create([
            'title' => $request->title,
            'branch' => $request->branch,
        ]);

        return redirect()->route('questionset.index')->with('success', 'تم إنشاء باقة الأسئلة بنجاح');
    }

    public function show(Questionset $questionset)
{
    $questionsByLevel = $questionset->questions
        ->groupBy('difficulties');

    return view('questionset.show', compact('questionset', 'questionsByLevel'));
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

        return redirect()->route('questionset.index')->with('success', 'تم تحديث باقة الأسئلة بنجاح');
    }

    public function destroy(Questionset $questionset)
    {
        $questionset->delete();
        return back()->with('success', 'تم حذف باقة الأسئلة');
    }
}
