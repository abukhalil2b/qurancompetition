<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use Illuminate\Http\Request;

class QuestionsetController extends Controller
{
    public function index($id = 1)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = isset($levels[$id]) ? $id : 1;

        $level = $levels[$levelId];

        $questionsets = Questionset::withCount('questions')
            ->where('level', $levelId)
            ->get();

        return view('questionset.index', compact(
            'questionsets',
            'level',
            'levelId'
        ));
    }

    public function create($level)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = isset($levels[$level]) ? $level : 1;

        $level = $levels[$levelId];

        return view('questionset.create', compact(
            'level',
            'levelId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:1,2',
        ]);

        Questionset::create([
            'title' => $request->title,
            'level' => $request->level,
        ]);

        return redirect()
            ->route('questionset.index', $request->level)
            ->with('success', 'تم إنشاء باقة الأسئلة بنجاح');
    }

    public function show(Questionset $questionset)
{
    $questions = $questionset->questions()
        ->with('surat')
        ->orderBy('quran_surat_id')
        ->orderBy('aya_from')
        ->get();

    return view('questionset.show', compact('questionset', 'questions'));
}

    public function edit(Questionset $questionset)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = $questionset->level;

        $level = $levels[$levelId] ?? $levels[1];

        return view('questionset.edit', compact(
            'questionset',
            'level',
            'levelId'
        ));
    }

    public function update(Request $request, Questionset $questionset)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:1,2',
        ]);

        $questionset->update([
            'title' => $request->title,
            'level' => $request->level,
        ]);

        return redirect()
            ->route('questionset.index', $request->level)
            ->with('success', 'تم تحديث باقة الأسئلة بنجاح');
    }

    public function print()
    {
        $questionsets = Questionset::with('questions')
            ->has('questions')
            ->get();

        return view('questionset.print', compact('questionsets'));
    }

    public function destroy(Questionset $questionset)
    {
        $questionset->delete();

        return back()->with('success', 'تم حذف باقة الأسئلة');
    }
}
