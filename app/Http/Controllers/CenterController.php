<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centers = Center::withCount('committees')->latest('id')->get();

        return view('center.index', compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show(Center $center)
    {
        $center->load([
            'committees' => function ($query) {
                $query->with('stage')
                    ->orderByDesc('active')
                    ->latest('id');
            },
        ]);

        $committeeIds = Committee::where('center_id', $center->id)->pluck('id');

        $competitions = Competition::with(['student', 'questionset'])
            ->where('center_id', $center->id)
            ->whereIn('committee_id', $committeeIds)
            ->orderBy('present_at')
            ->get();

        return view('center.show', compact('center', 'competitions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Center $center)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Center $center)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Center $center)
    {
        //
    }
}
