<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Center;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with('center')->latest()->get();
        $centers = Center::all();

        return view('committee.index', compact('committees', 'centers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'branch' => 'nullable|string|max:255',
            'center_id' => 'required|exists:centers,id',
        ]);

        Committee::create([
            'title'     => $validated['title'],
            'gender'    => $validated['gender'],
            'branch'    => $validated['branch'] ?? null,
            'center_id' => $validated['center_id'],
        ]);

        return back()->with('success', 'تم إضافة اللجنة بنجاح');
    }
}
