<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use Illuminate\Http\Request;

class QuestionsetController extends Controller
{
    public function index($id = 1)
    {
        $ids = [
            1 => 'حفظ وتفسير',
            2 => 'حفظ',
        ];

        $level = $ids[$id];

        $questionsets = Questionset::withCount('questions')
            ->where('level', $level)
            ->get();

        return view('questionset.index', compact('questionsets', 'level'));
    }

    public function create($level)
    {
        return view('questionset.create', compact('level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);

        Questionset::create([
            'title' => $request->title,
            'level' => $request->level,
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
            'level' => 'required|string',
        ]);

        $questionset->update([
            'title' => $request->title,
            'level' => $request->level,
        ]);

        return redirect()->route('questionset.index')
            ->with('success', 'تم تحديث باقة الأسئلة بنجاح');
    }


    public function destroy(Questionset $questionset)
    {
        $questionset->delete();
        return back()->with('success', 'تم حذف باقة الأسئلة');
    }
}
