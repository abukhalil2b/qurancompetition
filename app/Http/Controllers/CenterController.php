<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;
use Carbon\Carbon;

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
        $todayStart = today()->startOfDay();
        $todayEnd = today()->endOfDay();

        // الدرجة العظمى لكل مستوى (تُعامل المستويات بشكل منفصل)
        $levels = [
            1 => ['title' => 'المستوى الأول',  'max' => 120],
            2 => ['title' => 'المستوى الثاني', 'max' => 100],
        ];

        $base = fn () => Competition::query()
            ->where('competitions.center_id', $center->id)
            ->whereIn('competitions.committee_id', $committeeIds);

        $withStudents = fn () => $base()
            ->join('students', 'students.id', '=', 'competitions.student_id');

        // =====================================================================
        // قائمة المتسابقين (حاضرو اليوم + من لم يحضر بعد، دون المنسحبين)
        // =====================================================================
        $listQuery = fn () => $base()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->where(fn ($q) => $q
                ->whereBetween('competitions.present_at', [$todayStart, $todayEnd])
                ->orWhereNull('competitions.present_at'));

        $competitions = $listQuery()
            ->with('student')
            ->orderBy('competitions.present_at')
            ->limit(500)
            ->get();

        // مراحل المتسابقين (تظهر داخل قسم القائمة)
        $listStatusCounts = $listQuery()
            ->selectRaw('competitions.student_status, COUNT(*) AS c')
            ->groupBy('competitions.student_status')
            ->pluck('c', 'student_status')
            ->map(fn ($v) => (int) $v);

        // =====================================================================
        // الإحصائيات العامة (أرقام فقط)
        // =====================================================================
        $s = $withStudents()
            ->selectRaw("
            COUNT(*) AS total_all,
            SUM(competitions.student_status != 'withdraw') AS total,
            SUM(competitions.student_status != 'withdraw' AND students.gender = 'male')   AS male,
            SUM(competitions.student_status != 'withdraw' AND students.gender = 'female') AS female,
            SUM(competitions.student_status = 'withdraw') AS withdraw,

            SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended,

            SUM(competitions.student_status = 'finish_competition') AS finished_total,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male')   AS finished_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female') AS finished_female,

            SUM(competitions.student_status = 'finish_competition' AND competitions.finished_at BETWEEN ? AND ?) AS today_total,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male'   AND competitions.finished_at BETWEEN ? AND ?) AS today_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female' AND competitions.finished_at BETWEEN ? AND ?) AS today_female,

            AVG(CASE WHEN competitions.student_status = 'finish_competition'
                      AND competitions.present_at IS NOT NULL
                      AND competitions.finished_at IS NOT NULL
                     THEN TIMESTAMPDIFF(MINUTE, competitions.present_at, competitions.finished_at) END) AS avg_minutes,

            SUM(competitions.student_status = 'finish_competition' AND competitions.final_score IS NULL) AS dq_finished_no_score,
            SUM(competitions.student_status != 'finish_competition' AND competitions.final_score IS NOT NULL) AS dq_score_not_finished
        ", [$todayStart, $todayEnd, $todayStart, $todayEnd, $todayStart, $todayEnd])
            ->first();

        $kpi = collect($s->getAttributes())->map(fn ($v) => (int) round((float) $v))->all();

        // =====================================================================
        // أعداد المستويات
        // =====================================================================
        $levelCounts = $withStudents()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->selectRaw("
            competitions.level,
            COUNT(*) AS total,
            SUM(students.gender = 'male')   AS male,
            SUM(students.gender = 'female') AS female,
            SUM(competitions.student_status = 'finish_competition') AS finished
        ")
            ->groupBy('competitions.level')
            ->get()
            ->keyBy(fn ($r) => (int) $r->level);

        // =====================================================================
        // الدرجات (بدون أسماء) - كل مستوى منفصل
        // =====================================================================
        $scoreRows = $withStudents()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->whereNotNull('competitions.final_score')
            ->get(['competitions.committee_id', 'competitions.level', 'competitions.final_score', 'students.gender'])
            ->map(fn ($r) => [
                'committee_id' => (int) $r->committee_id,
                'level' => (int) $r->level,
                'gender' => $r->gender,
                'score' => (float) $r->final_score,
            ]);

        // إحصاءات وصفية لمجموعة درجات
        $describe = function ($scores) {
            $n = $scores->count();

            if ($n === 0) {
                return ['count' => 0, 'max' => null, 'min' => null, 'avg' => null, 'median' => null, 'std' => null];
            }

            $avg = $scores->avg();

            return [
                'count' => $n,
                'max' => round($scores->max(), 2),
                'min' => round($scores->min(), 2),
                'avg' => round($avg, 2),
                'median' => round($scores->median(), 2),
                'std' => $n > 1 ? round(sqrt($scores->sum(fn ($v) => ($v - $avg) ** 2) / ($n - 1)), 2) : null,
            ];
        };

        $avgOf = fn ($scores) => $scores->isEmpty() ? null : round($scores->avg(), 2);

        $levelStats = collect($levels)->map(function ($meta, $level) use ($scoreRows, $levelCounts, $describe, $avgOf, $kpi) {
            $rows = $scoreRows->where('level', $level);
            $scores = $rows->pluck('score');
            $max = $meta['max'];

            // فئات التوزيع كنسب من الدرجة العظمى: 60% / 70% / 80% / 90%
            [$t1, $t2, $t3, $t4] = array_map(fn ($p) => round($max * $p, 2), [0.6, 0.7, 0.8, 0.9]);

            $bins = [
                ['label' => "أقل من {$t1}",            'count' => $scores->filter(fn ($v) => $v < $t1)->count()],
                ['label' => "{$t1} إلى أقل من {$t2}",  'count' => $scores->filter(fn ($v) => $v >= $t1 && $v < $t2)->count()],
                ['label' => "{$t2} إلى أقل من {$t3}",  'count' => $scores->filter(fn ($v) => $v >= $t2 && $v < $t3)->count()],
                ['label' => "{$t3} إلى أقل من {$t4}",  'count' => $scores->filter(fn ($v) => $v >= $t3 && $v < $t4)->count()],
                ['label' => "{$t4} فأكثر",             'count' => $scores->filter(fn ($v) => $v >= $t4)->count()],
            ];

            $binMax = max(1, max(array_column($bins, 'count')));
            $bins = array_map(fn ($b) => $b + ['width' => round(($b['count'] / $binMax) * 100)], $bins);

            $c = $levelCounts[$level] ?? null;
            $levelTotal = (int) ($c->total ?? 0);
            $levelFinished = (int) ($c->finished ?? 0);

            return [
                'level' => $level,
                'title' => $meta['title'],
                'max' => $meta['max'],
                'total' => $levelTotal,
                'male' => (int) ($c->male ?? 0),
                'female' => (int) ($c->female ?? 0),
                'finished' => $levelFinished,
                'finished_width' => $levelTotal > 0 ? round(($levelFinished / $levelTotal) * 100) : 0,
                'share' => ($kpi['total'] ?? 0) > 0 ? round(($levelTotal / $kpi['total']) * 100, 1) : 0,
                'scores' => $describe($scores),
                'avg_male' => $avgOf($rows->where('gender', 'male')->pluck('score')),
                'avg_female' => $avgOf($rows->where('gender', 'female')->pluck('score')),
                'bins' => $bins,
            ];
        })->values();

        // =====================================================================
        // اللجان (أعداد + درجات منفصلة لكل مستوى)
        // =====================================================================
        $committeeCounts = $base()
            ->selectRaw("
            competitions.committee_id,
            SUM(competitions.student_status != 'withdraw') AS total,
            SUM(competitions.student_status = 'finish_competition') AS finished,
            SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended
        ")
            ->groupBy('competitions.committee_id')
            ->get()
            ->keyBy('committee_id');

        $committeeRows = $center->committees->map(function ($committee) use ($committeeCounts, $scoreRows, $describe) {
            $c = $committeeCounts[$committee->id] ?? null;
            $total = (int) ($c->total ?? 0);
            $finished = (int) ($c->finished ?? 0);
            $mine = $scoreRows->where('committee_id', $committee->id);

            return [
                'committee' => $committee,
                'total' => $total,
                'attended' => (int) ($c->attended ?? 0),
                'finished' => $finished,
                'bar' => $total > 0 ? round(($finished / $total) * 100) : 0,
                'l1' => $describe($mine->where('level', 1)->pluck('score')),
                'l2' => $describe($mine->where('level', 2)->pluck('score')),
            ];
        });

        // =====================================================================
        // إنهاء المسابقة حسب الأيام (finished_at)
        // =====================================================================
        $rawDaily = $withStudents()
            ->where('competitions.student_status', 'finish_competition')
            ->selectRaw("
            DATE(competitions.finished_at) AS finish_date,
            COUNT(*) AS total,
            SUM(students.gender = 'male')   AS males,
            SUM(students.gender = 'female') AS females
        ")
            ->groupByRaw('DATE(competitions.finished_at)')
            ->orderByDesc('finish_date')
            ->get();

        $dailyMax = max(1, (int) $rawDaily->max('total'));

        $finishedByDate = $rawDaily->map(fn ($i) => [
            'date' => $i->finish_date ? Carbon::parse($i->finish_date)->format('d/m/Y') : 'غير محدد',
            'total' => (int) $i->total,
            'males' => (int) $i->males,
            'females' => (int) $i->females,
            'label' => $this->contestantsLabel((int) $i->total),
            'width' => round(((int) $i->total / $dailyMax) * 100),
        ]);

        return view('center.show', compact(
            'center',
            'competitions',
            'listStatusCounts',
            'kpi',
            'levelStats',
            'committeeRows',
            'finishedByDate'
        ));
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
