<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;

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

    public function show(Center $center)
    {
        // Committees (with stage), active first, newest first
        $center->load([
            'committees' => fn ($q) => $q->with('stage')->orderByDesc('active')->latest('id'),
        ]);

        // Already loaded above, so no extra query
        $committeeIds = $center->committees->pluck('id');

        // Shared base query for this center's competitions
        $base = fn () => Competition::query()
            ->where('competitions.center_id', $center->id)
            ->whereIn('competitions.committee_id', $committeeIds);

        // Today's competitors + those who have not attended yet. Withdrawn are excluded.
        $competitions = $base()
            ->with('student')
            ->where('competitions.student_status', '!=', 'withdraw')
            ->where(fn ($q) => $q
                ->whereBetween('competitions.present_at', [today()->startOfDay(), today()->endOfDay()])
                ->orWhereNull('competitions.present_at'))
            ->orderBy('competitions.present_at')
            ->get();

            // KPI counts, computed from the same collection the table shows
        $kpi = [
            'total' => $competitions->count(),
            'male' => $competitions->filter(fn ($c) => $c->student?->gender === 'male')->count(),
            'female' => $competitions->filter(fn ($c) => $c->student?->gender === 'female')->count(),
            'level1' => $competitions->where('level', 1)->count(),
            'level2' => $competitions->where('level', 2)->count(),
            'withdraw' => $base()->where('student_status', 'withdraw')->count()
        ];

        // One query: per-day breakdown, and the overall totals are summed from it
        $finishedByDate = $base()
            ->join('students', 'students.id', '=', 'competitions.student_id')
            ->where('competitions.student_status', 'finish_competition')
            ->selectRaw("
            DATE(competitions.present_at) AS finish_date,
            COUNT(*) AS total,
            SUM(students.gender = 'male') AS males,
            SUM(students.gender = 'female') AS females
        ")
            ->groupByRaw('DATE(competitions.present_at)')
            ->orderByDesc('finish_date')
            ->get();

        $finishedTotal = (int) $finishedByDate->sum('total');
        $finishedMales = (int) $finishedByDate->sum('males');
        $finishedFemales = (int) $finishedByDate->sum('females');

        return view('center.show', compact(
            'center',
            'competitions',
            'kpi',
            'finishedByDate',
            'finishedTotal',
            'finishedMales',
            'finishedFemales'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | app/Models/Competition.php  (required, otherwise present_at->format() fails)
    |--------------------------------------------------------------------------
    |
    | protected $casts = [
    |     'present_at'  => 'datetime',
    |     'final_score' => 'decimal:2',
    |     'level'       => 'integer',
    | ];
    |
    | Optional migration for speed:
    | $table->index(['center_id', 'committee_id', 'student_status']);
    |
    | If students use SoftDeletes, add to the join query:
    | ->whereNull('students.deleted_at')
    */

}
