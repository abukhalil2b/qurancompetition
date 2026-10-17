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
    $center->load([
        'committees' => fn ($q) => $q->with('stage')->orderByDesc('active')->latest('id'),
    ]);

    $committeeIds = $center->committees->pluck('id');

    $base = fn () => Competition::query()
        ->where('competitions.center_id', $center->id)
        ->whereIn('competitions.committee_id', $committeeIds);

    // جدول متسابقي اليوم + من لم يحضر بعد (بدون المنسحبين)
    $competitions = $base()
        ->with('student')
        ->where('competitions.student_status', '!=', 'withdraw')
        ->where(fn ($q) => $q
            ->whereBetween('competitions.present_at', [today()->startOfDay(), today()->endOfDay()])
            ->orWhereNull('competitions.present_at'))
        ->orderBy('competitions.present_at')
        ->get();

    // كل الإحصائيات في استعلام واحد
    $today = today()->toDateString();

    $s = $base()
        ->join('students', 'students.id', '=', 'competitions.student_id')
        ->selectRaw("
            SUM(competitions.student_status != 'withdraw') AS total,

            SUM(competitions.student_status != 'withdraw' AND competitions.level = 1 AND students.gender = 'male')   AS l1_male,
            SUM(competitions.student_status != 'withdraw' AND competitions.level = 1 AND students.gender = 'female') AS l1_female,
            SUM(competitions.student_status != 'withdraw' AND competitions.level = 1) AS l1_total,

            SUM(competitions.student_status != 'withdraw' AND competitions.level = 2 AND students.gender = 'male')   AS l2_male,
            SUM(competitions.student_status != 'withdraw' AND competitions.level = 2 AND students.gender = 'female') AS l2_female,
            SUM(competitions.student_status != 'withdraw' AND competitions.level = 2) AS l2_total,

            SUM(competitions.student_status = 'withdraw') AS withdraw,

            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male')   AS finished_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female') AS finished_female,
            SUM(competitions.student_status = 'finish_competition') AS finished_total,

            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male'   AND DATE(competitions.present_at) = ?) AS today_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female' AND DATE(competitions.present_at) = ?) AS today_female,
            SUM(competitions.student_status = 'finish_competition' AND DATE(competitions.present_at) = ?) AS today_total
        ", [$today, $today, $today])
        ->first();

    $kpi = collect($s->getAttributes())->map(fn ($v) => (int) $v)->all();

    // التفصيل اليومي للمنتهين (للجدول)
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

    return view('center.show', compact('center', 'competitions', 'kpi', 'finishedByDate'));
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
