<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Committee;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class HomeController extends Controller
{

public function statisticDashboard(Request $request)
{
    // ---------- الفلتر: كل المراكز أو مركز واحد ----------
    $centers = Center::orderBy('title')->get(['id', 'title']);
    $selectedCenter = $request->filled('center_id')
        ? $centers->firstWhere('id', (int) $request->center_id)
        : null;

    $todayStart = today()->startOfDay();
    $todayEnd = today()->endOfDay();

    $levels = [
        1 => ['title' => 'المستوى الأول',  'max' => 120],
        2 => ['title' => 'المستوى الثاني', 'max' => 100],
    ];

    $base = fn () => Competition::query()
        ->whereNotNull('competitions.committee_id')
        ->when($selectedCenter, fn ($q) => $q->where('competitions.center_id', $selectedCenter->id));

    $withStudents = fn () => $base()
        ->join('students', 'students.id', '=', 'competitions.student_id');

    // ---------- 1. المؤشرات العامة ----------
    $s = $withStudents()->selectRaw("
        COUNT(*) AS total_all,
        SUM(competitions.student_status != 'withdraw') AS total,
        SUM(competitions.student_status != 'withdraw' AND students.gender = 'male')   AS male,
        SUM(competitions.student_status != 'withdraw' AND students.gender = 'female') AS female,
        SUM(competitions.student_status = 'withdraw') AS withdraw,
        SUM(competitions.student_status = 'withdraw' AND students.gender = 'male')   AS withdraw_male,
        SUM(competitions.student_status = 'withdraw' AND students.gender = 'female') AS withdraw_female,

        SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended,
        SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw' AND students.gender = 'male')   AS attended_male,
        SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw' AND students.gender = 'female') AS attended_female,
        SUM(competitions.present_at IS NULL AND competitions.student_status != 'withdraw') AS not_attended,

        SUM(competitions.student_status = 'finish_competition') AS finished_total,
        SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male')   AS finished_male,
        SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female') AS finished_female,

        SUM(competitions.student_status = 'finish_competition' AND competitions.finished_at BETWEEN ? AND ?) AS today_total,
        SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male'   AND competitions.finished_at BETWEEN ? AND ?) AS today_male,
        SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female' AND competitions.finished_at BETWEEN ? AND ?) AS today_female,

        AVG(CASE WHEN competitions.student_status = 'finish_competition'
                  AND competitions.with_committee_at IS NOT NULL AND competitions.finished_at IS NOT NULL
                 THEN TIMESTAMPDIFF(MINUTE, competitions.with_committee_at, competitions.finished_at) END) AS avg_test_minutes,
        MIN(CASE WHEN competitions.student_status = 'finish_competition'
                  AND competitions.with_committee_at IS NOT NULL AND competitions.finished_at IS NOT NULL
                 THEN TIMESTAMPDIFF(MINUTE, competitions.with_committee_at, competitions.finished_at) END) AS min_test_minutes,
        MAX(CASE WHEN competitions.student_status = 'finish_competition'
                  AND competitions.with_committee_at IS NOT NULL AND competitions.finished_at IS NOT NULL
                 THEN TIMESTAMPDIFF(MINUTE, competitions.with_committee_at, competitions.finished_at) END) AS max_test_minutes,

        AVG(CASE WHEN competitions.present_at IS NOT NULL AND competitions.with_committee_at IS NOT NULL
                 THEN TIMESTAMPDIFF(MINUTE, competitions.present_at, competitions.with_committee_at) END) AS avg_wait_minutes,

        SUM(competitions.student_status = 'finish_competition' AND competitions.final_score IS NULL) AS dq_finished_no_score,
        SUM(competitions.student_status != 'finish_competition' AND competitions.final_score IS NOT NULL) AS dq_score_not_finished
    ", [$todayStart, $todayEnd, $todayStart, $todayEnd, $todayStart, $todayEnd])->first();

    $kpi = collect($s->getAttributes())->map(fn ($v) => (int) round((float) $v))->all();
    $kpi['attendance_rate'] = $kpi['total'] > 0 ? round($kpi['attended'] / $kpi['total'] * 100, 1) : 0;
    $kpi['completion_rate'] = $kpi['total'] > 0 ? round($kpi['finished_total'] / $kpi['total'] * 100, 1) : 0;
    $kpi['withdraw_rate']   = $kpi['total_all'] > 0 ? round($kpi['withdraw'] / $kpi['total_all'] * 100, 1) : 0;

    $statusBreakdown = $base()
        ->selectRaw('competitions.student_status, COUNT(*) AS count')
        ->groupBy('competitions.student_status')
        ->pluck('count', 'student_status')
        ->map(fn ($v) => (int) $v);

    // ---------- 2. المستويات ----------
    $levelCounts = $withStudents()
        ->where('competitions.student_status', '!=', 'withdraw')
        ->selectRaw("
            competitions.level,
            COUNT(*) AS total,
            SUM(students.gender = 'male')   AS male,
            SUM(students.gender = 'female') AS female,
            SUM(competitions.present_at IS NOT NULL) AS attended,
            SUM(competitions.student_status = 'finish_competition') AS finished,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male')   AS finished_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female') AS finished_female
        ")
        ->groupBy('competitions.level')
        ->get()
        ->keyBy(fn ($r) => (int) $r->level);

    // ---------- 3. الدرجات ----------
    $scoreRows = $withStudents()
        ->where('competitions.student_status', '!=', 'withdraw')
        ->whereNotNull('competitions.final_score')
        ->get(['competitions.center_id', 'competitions.committee_id', 'competitions.level', 'competitions.final_score', 'students.gender'])
        ->map(fn ($r) => [
            'center_id'    => (int) $r->center_id,
            'committee_id' => (int) $r->committee_id,
            'level'        => (int) $r->level,
            'gender'       => $r->gender,
            'score'        => (float) $r->final_score,
        ]);

    $avgOf = fn ($scores) => $scores->isEmpty() ? null : round($scores->avg(), 2);

    $levelStats = collect($levels)->map(function ($meta, $level) use ($scoreRows, $levelCounts, $avgOf, $kpi) {
        $rows = $scoreRows->where('level', $level);
        $scores = $rows->pluck('score');
        $max = $meta['max'];

        [$t1, $t2, $t3, $t4] = array_map(fn ($p) => round($max * $p, 2), [0.6, 0.7, 0.8, 0.9]);

        $bins = [
            ['label' => 'أقل من 60%', 'from' => 0,   'to' => $t1,  'count' => $scores->filter(fn ($v) => $v < $t1)->count(),                   'tone' => 'rose'],
            ['label' => '60% - 69%',  'from' => $t1, 'to' => $t2,  'count' => $scores->filter(fn ($v) => $v >= $t1 && $v < $t2)->count(),        'tone' => 'amber'],
            ['label' => '70% - 79%',  'from' => $t2, 'to' => $t3,  'count' => $scores->filter(fn ($v) => $v >= $t2 && $v < $t3)->count(),        'tone' => 'sky'],
            ['label' => '80% - 89%',  'from' => $t3, 'to' => $t4,  'count' => $scores->filter(fn ($v) => $v >= $t3 && $v < $t4)->count(),        'tone' => 'teal'],
            ['label' => '90% فأعلى',  'from' => $t4, 'to' => $max, 'count' => $scores->filter(fn ($v) => $v >= $t4)->count(),                   'tone' => 'emerald'],
        ];

        $n = $scores->count();
        $binMax = max(1, max(array_column($bins, 'count')));
        $bins = array_map(fn ($b) => $b + [
            'width' => round($b['count'] / $binMax * 100),
            'pct'   => $n > 0 ? round($b['count'] / $n * 100, 1) : 0,
        ], $bins);

        $c = $levelCounts[$level] ?? null;
        $levelTotal = (int) ($c->total ?? 0);
        $levelFinished = (int) ($c->finished ?? 0);

        return [
            'level'           => $level,
            'title'           => $meta['title'],
            'max'             => $max,
            'total'           => $levelTotal,
            'male'            => (int) ($c->male ?? 0),
            'female'          => (int) ($c->female ?? 0),
            'attended'        => (int) ($c->attended ?? 0),
            'finished'        => $levelFinished,
            'finished_male'   => (int) ($c->finished_male ?? 0),
            'finished_female' => (int) ($c->finished_female ?? 0),
            'finished_width'  => $levelTotal > 0 ? round($levelFinished / $levelTotal * 100, 1) : 0,
            'share'           => $kpi['total'] > 0 ? round($levelTotal / $kpi['total'] * 100, 1) : 0,
            'scores'          => $this->describeScores($scores, $max),
            'avg_male'        => $avgOf($rows->where('gender', 'male')->pluck('score')),
            'avg_female'      => $avgOf($rows->where('gender', 'female')->pluck('score')),
            'bins'            => $bins,
        ];
    })->values();

    // ---------- 4. التفصيل: حسب المركز (كل المراكز) أو حسب اللجنة (مركز محدد) ----------
    $groupCol = $selectedCenter ? 'committee_id' : 'center_id';

    $groupCounts = $base()
        ->selectRaw("
            competitions.{$groupCol} AS gid,
            SUM(competitions.student_status != 'withdraw') AS total,
            SUM(competitions.student_status = 'withdraw') AS withdraw,
            SUM(competitions.student_status = 'finish_competition') AS finished,
            SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended
        ")
        ->groupBy("competitions.{$groupCol}")
        ->get()
        ->keyBy('gid');

    if ($selectedCenter) {
        $committees = Committee::with('stage')
            ->where('center_id', $selectedCenter->id)
            ->orderByDesc('active')->latest('id')
            ->get();
        $entities = $committees->map(fn ($c) => ['id' => $c->id, 'label' => $c->name ?? ('لجنة #' . $c->id), 'sub' => $c->stage->name ?? null]);
    } else {
        $entities = $centers->map(fn ($c) => ['id' => $c->id, 'label' => $c->name, 'sub' => null]);
    }

    $groupRows = $entities->map(function ($e) use ($groupCounts, $scoreRows, $groupCol, $levels) {
        $c = $groupCounts[$e['id']] ?? null;
        $total = (int) ($c->total ?? 0);
        $finished = (int) ($c->finished ?? 0);
        $mine = $scoreRows->where($groupCol, $e['id']);

        return $e + [
            'total'    => $total,
            'withdraw' => (int) ($c->withdraw ?? 0),
            'attended' => (int) ($c->attended ?? 0),
            'finished' => $finished,
            'bar'      => $total > 0 ? round($finished / $total * 100, 1) : 0,
            'l1'       => $this->describeScores($mine->where('level', 1)->pluck('score'), $levels[1]['max']),
            'l2'       => $this->describeScores($mine->where('level', 2)->pluck('score'), $levels[2]['max']),
        ];
    })->filter(fn ($r) => $r['total'] > 0 || $r['withdraw'] > 0)->values();

    // ---------- 5. الإنهاء حسب الأيام (آخر 30 يومًا) ----------
    $rawDaily = $withStudents()
        ->where('competitions.student_status', 'finish_competition')
        ->whereNotNull('competitions.finished_at')
        ->selectRaw("
            DATE(competitions.finished_at) AS finish_date,
            COUNT(*) AS total,
            SUM(students.gender = 'male')   AS males,
            SUM(students.gender = 'female') AS females,
            SUM(competitions.level = 1)     AS level_1,
            SUM(competitions.level = 2)     AS level_2
        ")
        ->groupByRaw('DATE(competitions.finished_at)')
        ->orderByDesc('finish_date')
        ->limit(30)
        ->get();

    $dailyMax = max(1, (int) $rawDaily->max('total'));

    $finishedByDate = $rawDaily->map(fn ($i) => [
        'date'    => Carbon::parse($i->finish_date)->format('Y/m/d'),
        'day_ar'  => Carbon::parse($i->finish_date)->locale('ar')->dayName,
        'total'   => (int) $i->total,
        'males'   => (int) $i->males,
        'females' => (int) $i->females,
        'level_1' => (int) $i->level_1,
        'level_2' => (int) $i->level_2,
        'label'   => $this->contestantsLabel((int) $i->total),
        'width'   => round((int) $i->total / $dailyMax * 100),
        'pct'     => $kpi['finished_total'] > 0 ? round((int) $i->total / $kpi['finished_total'] * 100, 1) : 0,
    ]);

    return view('statistic_dashboard', compact(
        'centers', 'selectedCenter', 'kpi', 'statusBreakdown',
        'levelStats', 'groupRows', 'finishedByDate'
    ));
}

private function describeScores($scores, $maxMark = 100): array
{
    $n = $scores->count();

    if ($n === 0) {
        return ['count' => 0, 'max' => null, 'min' => null, 'avg' => null, 'avg_pct' => null, 'median' => null, 'std' => null];
    }

    $avg = $scores->avg();

    return [
        'count'   => $n,
        'max'     => round($scores->max(), 2),
        'min'     => round($scores->min(), 2),
        'avg'     => round($avg, 2),
        'avg_pct' => $maxMark > 0 ? round($avg / $maxMark * 100, 1) : null,
        'median'  => round($scores->median(), 2),
        'std'     => $n > 1 ? round(sqrt($scores->sum(fn ($v) => ($v - $avg) ** 2) / ($n - 1)), 2) : null,
    ];
}

private function contestantsLabel(int $n): string
{
    return match (true) {
        $n === 1 => 'متسابق',
        $n === 2 => 'متسابقان',
        $n >= 3 && $n <= 10 => 'متسابقين',
        default => 'متسابقًا',
    };
}

    public function testDashboard()
    {

        $users = User::all();

        return view('test_dashboard', compact('users'));
    }

    public function testLoginToUserAccount(Request $request, User $user)
    {
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function welcome()
    {
        // return view('report');
        return view('welcome');
    }

    public function dashboard()
    {

        $activeStage = Stage::where('active', 1)->first();

        if (! $activeStage) {
            abort(404);
        }

        $loggedUser = auth()->user();

        if ($loggedUser->user_type == 'organizer') {
            return redirect()->route('student.attendance_index');
        }

        if ($loggedUser->user_type == 'judge') {

            // dashboard for judge
            $committeeUser = CommitteeUser::where('user_id', $loggedUser->id)->first();

            if (! $committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            $readyStudent = Competition::where('committee_id', $committeeUser->committee_id)->first();

            return view('judge_dashboard', compact('readyStudent', 'activeStage'));
        }

        if ($loggedUser->user_type == 'commite') {

            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (! $committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            return view('commite_dashboard', compact('activeStage'));
        }

        return view('dashboard', compact('activeStage'));
    }
}
