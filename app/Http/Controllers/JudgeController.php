<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Committee;
use Illuminate\Http\Request;

class JudgeController extends Controller
{
    public function index()
    {
        $judges = User::where('user_type', 'judge')->get();

        return view('judge.index', compact('judges'));
    }

    public function show(User $judge)
    {
        // load committees linked to the judge
        $judge->load('committees');

        // all committees from all centers
        $committees = Committee::with('center')->get();

        return view('judge.show', compact('judge', 'committees'));
    }

    public function create()
    {
        return view('judge.create');
    }
 
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'gender'      => 'required|in:male,female',
        'national_id' => 'nullable|string|max:10|unique:users,national_id',
    ]);

    User::create([
        'name'        => $validated['name'],
        'gender'      => $validated['gender'],
        'national_id' => $validated['national_id'] ?? null,
        'user_type'   => 'judge',
        'password'    => bcrypt('123456'), // default
    ]);

    return redirect()->route('judge.index')
                     ->with('success', 'تم إضافة المحكم بنجاح');
}


    public function assignCommittee(Request $request)
    {
        $validated = $request->validate([
            'judge_id' => 'required|exists:users,id',
            'committee_id' => 'required|exists:committees,id'
        ]);

        $judge = User::findOrFail($validated['judge_id']);

        // Prevent duplicates
        if (!$judge->committees()->where('committee_id', $validated['committee_id'])->exists()) {
            $judge->committees()->attach($validated['committee_id']);
        }

        return back()->with('success', 'تم ربط المحكم باللجنة بنجاح');
    }
}
