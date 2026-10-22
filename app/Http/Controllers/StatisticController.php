<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function statisticDashboard()
    {
        $levels = [
            1 => ['title' => 'المستوى الأول', 'max' => 120],
            2 => ['title' => 'المستوى الثاني', 'max' => 100],
        ];

        $base = fn () => Competition::query()
            ->whereNotNull('competitions.committee_id');

        $withStudents = fn () => $base()
            ->join('students', 'students.id', '=', 'competitions.student_id');

        // المؤشرات العامة المعروضة في الصفحة
        $s = $withStudents()->selectRaw("
            COUNT(*) AS total_all,
            SUM(competitions.student_status != 'withdraw') AS total,
            SUM(competitions.student_status != 'withdraw' AND students.gender = 'male') AS male,
            SUM(competitions.student_status != 'withdraw' AND students.gender = 'female') AS female,
            SUM(competitions.student_status = 'withdraw') AS withdraw,
            SUM(competitions.student_status = 'withdraw' AND students.gender = 'male') AS withdraw_male,
            SUM(competitions.student_status = 'withdraw' AND students.gender = 'female') AS withdraw_female,
            
            SUM(competitions.student_status = 'finish_competition') AS finished_total,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'male') AS finished_male,
            SUM(competitions.student_status = 'finish_competition' AND students.gender = 'female') AS finished_female,
            
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
        ")->first();

        $kpi = collect($s->getAttributes())->map(function ($value, $key) {
            if ($value === null) {
                return null;
            }

            if (in_array($key, ['avg_test_minutes', 'min_test_minutes', 'max_test_minutes', 'avg_wait_minutes'], true)) {
                return round((float) $value, 1);
            }

            return (int) round((float) $value);
        })->all();
        
        $kpi['completion_rate'] = $kpi['total'] > 0 ? round($kpi['finished_total'] / $kpi['total'] * 100, 1) : 0;
        $kpi['withdraw_rate'] = $kpi['total_all'] > 0 ? round($kpi['withdraw'] / $kpi['total_all'] * 100, 1) : 0;

        $scoreRows = $withStudents()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->whereNotNull('competitions.final_score')
            ->get([
                'competitions.center_id',
                'competitions.level',
                'competitions.final_score',
                'students.gender',
            ])
            ->map(fn ($r) => [
                'center_id' => (int) $r->center_id,
                'level' => (int) $r->level,
                'gender' => $r->gender,
                'score' => (float) $r->final_score,
            ]);

        $avgOf = fn ($scores) => $scores->isEmpty() ? null : round($scores->avg(), 2);

        $buildLevelStats = function (int $level, $rows, int $total, int $male, int $female, int $attended, int $finished) use ($levels, $avgOf) {
            $meta = $levels[$level];
            $scores = $rows->pluck('score');
            $max = $meta['max'];

            if ($level === 1) {
                $ranges = [
                    ['label' => 'أقل من 60% (أقل من 72)', 'min' => null, 'max' => 72, 'tone' => 'rose'],
                    ['label' => '60% – أقل من 70% (72–أقل من 84)', 'min' => 72, 'max' => 84, 'tone' => 'amber'],
                    ['label' => '70% – أقل من 80% (84–أقل من 96)', 'min' => 84, 'max' => 96, 'tone' => 'sky'],
                    ['label' => '80% – أقل من 90% (96–أقل من 108)', 'min' => 96, 'max' => 108, 'tone' => 'teal'],
                    ['label' => 'من 108 إلى أقل من 110 درجة', 'min' => 108, 'max' => 110, 'tone' => 'sky'],
                    ['label' => 'من 110 إلى 120 درجة', 'min' => 110, 'max' => 120, 'inclusive_max' => true, 'tone' => 'emerald'],
                ];

                $bins = collect($ranges)->map(function ($range) use ($scores) {
                    $count = $scores->filter(function ($score) use ($range) {
                        $aboveMin = !array_key_exists('min', $range) || $range['min'] === null
                            || (isset($range['min_exclusive']) && $range['min_exclusive']
                                ? $score > $range['min']
                                : $score >= $range['min']);
                        $belowMax = !array_key_exists('max', $range) || $range['max'] === null
                            || (isset($range['inclusive_max']) && $range['inclusive_max']
                                ? $score <= $range['max']
                                : $score < $range['max']);

                        return $aboveMin && $belowMax;
                    })->count();

                    return [
                        'label' => $range['label'],
                        'count' => $count,
                        'tone' => $range['tone'],
                        'pct' => $scores->count() ? round($count / $scores->count() * 100, 1) : 0,
                    ];
                })->all();
            } else {
                $ranges = [
                    ['label' => 'أقل من 60%', 'from' => null, 'to' => .60, 'tone' => 'rose'],
                    ['label' => '60% – أقل من 70%', 'from' => .60, 'to' => .70, 'tone' => 'amber'],
                    ['label' => '70% – أقل من 80%', 'from' => .70, 'to' => .80, 'tone' => 'sky'],
                    ['label' => '80% – أقل من 90%', 'from' => .80, 'to' => .90, 'tone' => 'teal'],
                    ['label' => '90% – أقل من 100%', 'from' => .90, 'to' => 1.00, 'tone' => 'emerald'],
                ];

                $bins = collect($ranges)->map(function ($range) use ($scores, $max) {
                    $lower = $range['from'] === null ? null : $max * $range['from'];
                    $upper = $range['to'] === null ? null : $max * $range['to'];
                    
                    $count = $scores->filter(fn ($score) =>
                        ($lower === null || $score >= $lower) &&
                        ($upper === null || $score < $upper)
                    )->count();

                    return [
                        'label' => $range['label'],
                        'count' => $count,
                        'tone' => $range['tone'],
                        'pct' => $scores->count() ? round($count / $scores->count() * 100, 1) : 0,
                    ];
                })->all();
            }

            return [
                'level' => $level,
                'title' => $meta['title'],
                'max' => $max,
                'total' => $total,
                'male' => $male,
                'female' => $female,
                'attended' => $attended,
                'finished' => $finished,
                'finished_width' => $total > 0 ? round($finished / $total * 100, 1) : 0,
                'scores' => $this->describeScores($scores, $max),
                'avg_male' => $avgOf($rows->where('gender', 'male')->pluck('score')),
                'avg_female' => $avgOf($rows->where('gender', 'female')->pluck('score')),
                'bins' => $bins,
            ];
        };

        $levelCounts = $withStudents()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->selectRaw("
                competitions.level,
                COUNT(*) AS total,
                SUM(students.gender = 'male') AS male,
                SUM(students.gender = 'female') AS female,
                SUM(competitions.present_at IS NOT NULL) AS attended,
                SUM(competitions.student_status = 'finish_competition') AS finished
            ")
            ->groupBy('competitions.level')
            ->get()
            ->keyBy(fn ($r) => (int) $r->level);

        $levelStats = collect($levels)->map(function ($meta, $level) use ($scoreRows, $levelCounts, $buildLevelStats) {
            $c = $levelCounts[$level] ?? null;
            return $buildLevelStats(
                (int) $level,
                $scoreRows->where('level', (int) $level),
                (int) ($c->total ?? 0),
                (int) ($c->male ?? 0),
                (int) ($c->female ?? 0),
                (int) ($c->attended ?? 0),
                (int) ($c->finished ?? 0),
            );
        })->values();

        $centerCounts = $base()
            ->selectRaw("
                competitions.center_id,
                COUNT(*) AS total_all,
                SUM(competitions.student_status != 'withdraw') AS total,
                SUM(competitions.student_status = 'withdraw') AS withdraw,
                SUM(competitions.student_status = 'finish_competition') AS finished,
                SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended
            ")
            ->groupBy('competitions.center_id')
            ->get()
            ->keyBy('center_id');

        $centerLevelCounts = $withStudents()
            ->where('competitions.student_status', '!=', 'withdraw')
            ->selectRaw("
                competitions.center_id,
                competitions.level,
                COUNT(*) AS total,
                SUM(students.gender = 'male') AS male,
                SUM(students.gender = 'female') AS female,
                SUM(competitions.present_at IS NOT NULL) AS attended,
                SUM(competitions.student_status = 'finish_competition') AS finished
            ")
            ->groupBy('competitions.center_id', 'competitions.level')
            ->get()
            ->groupBy('center_id');

        $centers = Center::query()
            ->whereIn('id', $centerCounts->keys())
            ->orderBy('title')
            ->get(['id', 'title']);

        $centerSections = $centers->map(function ($center) use ($centerCounts, $centerLevelCounts, $scoreRows, $buildLevelStats) {
            $summary = $centerCounts[$center->id] ?? null;
            $levelsForCenter = $centerLevelCounts->get($center->id, collect())->keyBy(fn ($r) => (int) $r->level);

            $levelSections = collect([1, 2])->map(function ($level) use ($center, $levelsForCenter, $scoreRows, $buildLevelStats) {
                $c = $levelsForCenter[$level] ?? null;
                $rows = $scoreRows->where('center_id', (int) $center->id)->where('level', $level);

                return $buildLevelStats(
                    $level,
                    $rows,
                    (int) ($c->total ?? 0),
                    (int) ($c->male ?? 0),
                    (int) ($c->female ?? 0),
                    (int) ($c->attended ?? 0),
                    (int) ($c->finished ?? 0),
                );
            })->values();

            return [
                'id' => $center->id,
                'title' => $center->title,
                'total_all' => (int) ($summary->total_all ?? 0),
                'total' => (int) ($summary->total ?? 0),
                'withdraw' => (int) ($summary->withdraw ?? 0),
                'attended' => (int) ($summary->attended ?? 0),
                'finished' => (int) ($summary->finished ?? 0),
                'completion_rate' => ($summary && $summary->total > 0) ? round($summary->finished / $summary->total * 100, 1) : 0,
                'levels' => $levelSections,
            ];
        })->values();

        $rawDaily = $withStudents()
            ->where('competitions.student_status', 'finish_competition')
            ->whereNotNull('competitions.finished_at')
            ->selectRaw("
                DATE(competitions.finished_at) AS finish_date,
                COUNT(*) AS total,
                SUM(students.gender = 'male') AS males,
                SUM(students.gender = 'female') AS females,
                SUM(competitions.level = 1) AS level_1,
                SUM(competitions.level = 2) AS level_2
            ")
            ->groupByRaw('DATE(competitions.finished_at)')
            ->orderByDesc('finish_date')
            ->limit(30)
            ->get();

        $dailyMax = max(1, (int) $rawDaily->max('total'));
        $finishedByDate = $rawDaily->map(fn ($i) => [
            'date' => Carbon::parse($i->finish_date)->format('Y/m/d'),
            'day_ar' => Carbon::parse($i->finish_date)->locale('ar')->dayName,
            'total' => (int) $i->total,
            'males' => (int) $i->males,
            'females' => (int) $i->females,
            'level_1' => (int) $i->level_1,
            'level_2' => (int) $i->level_2,
            'label' => $this->contestantsLabel((int) $i->total),
            'width' => round((int) $i->total / $dailyMax * 100),
            'pct' => (int) $kpi['finished_total'] > 0 ? round((int) $i->total / $kpi['finished_total'] * 100, 1) : 0,
        ]);

        return view('statistic_dashboard', compact(
            'kpi',
            'levelStats',
            'centerSections',
            'finishedByDate'
        ));
    }


    private function describeScores($scores, $maxMark = 100): array
    {
        $n = $scores->count();

        if ($n === 0) {
            return [
                'count' => 0, 'max' => null, 'min' => null, 'avg' => null,
                'avg_pct' => null, 'median' => null, 'std' => null,
            ];
        }

        $avg = $scores->avg();

        return [
            'count' => $n,
            'max' => round($scores->max(), 2),
            'min' => round($scores->min(), 2),
            'avg' => round($avg, 2),
            'avg_pct' => $maxMark > 0 ? round($avg / $maxMark * 100, 1) : null,
            'median' => round($scores->median(), 2),
            'std' => $n > 1
                ? round(sqrt($scores->sum(fn ($value) => ($value - $avg) ** 2) / ($n - 1)), 2)
                : null,
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
}