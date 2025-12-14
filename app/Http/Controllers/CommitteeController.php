<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Center;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['center','judges'])->latest('id')->get();
        
        $centers = Center::all();

        return view('committee.index', compact('committees', 'centers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|string|max:7',
            'title' => 'required|string|max:255',
            'center_id' => 'required|exists:centers,id',
        ]);

        Committee::create([
            'title'     => $validated['title'],
            'center_id' => $validated['center_id'],
            'gender' => $validated['gender'],
        ]);

        return back()->with('success', 'تم إضافة اللجنة بنجاح');
    }
}
