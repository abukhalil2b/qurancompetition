<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Competition;
use Illuminate\Support\Carbon;

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
            
            SUM(competitions.present_at IS NOT NULL AND competitions.student_status != 'withdraw') AS attended_total,
            
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
        $kpi['attendance_rate'] = $kpi['total'] > 0 ? round($kpi['attended_total'] / $kpi['total'] * 100, 1) : 0;
        $kpi['remaining_total'] = max(0, $kpi['total'] - $kpi['finished_total']);
        $kpi['estimated_remaining_hours'] = round(($kpi['remaining_total'] * ($kpi['avg_test_minutes'] ?? 20)) / 60, 1);

        // الارتباط الإحصائي بين المدة والنسبة المئوية للدرجة لتفادي اختلاف المقاييس بين المستويات
        $finishedWithTime = $withStudents()
            ->where('competitions.student_status', 'finish_competition')
            ->whereNotNull('competitions.final_score')
            ->whereNotNull('competitions.with_committee_at')
            ->whereNotNull('competitions.finished_at')
            ->selectRaw("
                competitions.level,
                competitions.final_score,
                TIMESTAMPDIFF(MINUTE, competitions.with_committee_at, competitions.finished_at) AS duration
            ")
            ->get();

        $correlationTimeScore = null;
        if ($finishedWithTime->count() > 2) {
            $pctScores = [];
            $durations = [];

            foreach ($finishedWithTime as $row) {
                $maxScore = $levels[$row->level]['max'] ?? 100;
                $pctScores[] = ((float) $row->final_score / $maxScore) * 100;
                $durations[] = (float) $row->duration;
            }

            $correlationTimeScore = $this->calculateCorrelation($durations, $pctScores);
        }
        $kpi['correlation_time_score'] = $correlationTimeScore;

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
            $n = $scores->count();

            $ranges = [
                ['label' => 'أقل من 60%',       'from' => null, 'to' => 0.60, 'tone' => 'rose'],
                ['label' => '60% – أقل من 70%', 'from' => 0.60, 'to' => 0.70, 'tone' => 'amber'],
                ['label' => '70% – أقل من 80%', 'from' => 0.70, 'to' => 0.80, 'tone' => 'sky'],
                ['label' => '80% – أقل من 90%', 'from' => 0.80, 'to' => 0.90, 'tone' => 'teal'],
                ['label' => '90% – 100%',       'from' => 0.90, 'to' => 1.00, 'tone' => 'emerald', 'inclusive_max' => true],
            ];

            $bins = collect($ranges)->map(function ($range) use ($scores, $max, $n) {
                $lower = $range['from'] === null ? null : $max * $range['from'];
                $upper = $range['to'] === null ? null : $max * $range['to'];

                $count = $scores->filter(function ($score) use ($lower, $upper, $range) {
                    $aboveMin = $lower === null || $score >= $lower;
                    $belowMax = $upper === null || (
                        isset($range['inclusive_max']) && $range['inclusive_max']
                            ? $score <= $upper
                            : $score < $upper
                    );

                    return $aboveMin && $belowMax;
                })->count();

                return [
                    'label' => $range['label'],
                    'count' => $count,
                    'tone' => $range['tone'],
                    'pct' => $n ? round(($count / $n) * 100, 1) : 0,
                ];
            })->all();

            return [
                'level' => $level,
                'title' => $meta['title'],
                'max' => $max,
                'total' => $total,
                'male' => $male,
                'female' => $female,
                'attended' => $attended,
                'finished' => $finished,
                'finished_width' => $total > 0 ? round(($finished / $total) * 100, 1) : 0,
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
                'completion_rate' => ($summary && $summary->total > 0) ? round(($summary->finished / $summary->total) * 100, 1) : 0,
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
            'width' => round(((int) $i->total / $dailyMax) * 100),
            'pct' => (int) $kpi['finished_total'] > 0 ? round(((int) $i->total / $kpi['finished_total']) * 100, 1) : 0,
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
                'count' => 0, 'max' => null, 'min' => null, 'range' => null,
                'avg' => null, 'avg_pct' => null, 'median' => null, 'std' => null,
                'q1' => null, 'q3' => null, 'iqr' => null, 'cv' => null, 'skewness' => null,
            ];
        }

        $sorted = $scores->sort()->values();
        $avg = (float) $scores->avg();
        $median = (float) $scores->median();

        $std = $n > 1
            ? round(sqrt($scores->sum(fn ($v) => ($v - $avg) ** 2) / ($n - 1)), 2)
            : 0;

        $q1 = $this->getPercentile($sorted, 0.25);
        $q3 = $this->getPercentile($sorted, 0.75);
        $iqr = round($q3 - $q1, 2);

        $cv = $avg > 0 ? round(($std / $avg) * 100, 1) : 0;
        $skewness = ($std > 0) ? round((3 * ($avg - $median)) / $std, 2) : 0;

        return [
            'count' => $n,
            'max' => round($scores->max(), 2),
            'min' => round($scores->min(), 2),
            'range' => round($scores->max() - $scores->min(), 2),
            'avg' => round($avg, 2),
            'avg_pct' => $maxMark > 0 ? round(($avg / $maxMark) * 100, 1) : null,
            'median' => round($median, 2),
            'std' => $std,
            'q1' => round($q1, 2),
            'q3' => round($q3, 2),
            'iqr' => $iqr,
            'cv' => $cv,
            'skewness' => $skewness,
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

    private function getPercentile($sortedCollection, float $percentile): float
    {
        $count = $sortedCollection->count();
        if ($count === 0) {
            return 0.0;
        }

        $index = ($count - 1) * $percentile;
        $floor = (int) floor($index);
        $ceil = (int) ceil($index);

        if ($floor === $ceil) {
            return (float) $sortedCollection[$floor];
        }

        return (float) ($sortedCollection[$floor] + ($index - $floor) * ($sortedCollection[$ceil] - $sortedCollection[$floor]));
    }

    private function calculateCorrelation(array $x, array $y): ?float
    {
        $n = count($x);
        if ($n !== count($y) || $n < 2) {
            return null;
        }

        $meanX = array_sum($x) / $n;
        $meanY = array_sum($y) / $n;

        $num = 0.0;
        $denX = 0.0;
        $denY = 0.0;

        for ($i = 0; $i < $n; $i++) {
            $dx = $x[$i] - $meanX;
            $dy = $y[$i] - $meanY;
            $num += $dx * $dy;
            $denX += $dx * $dx;
            $denY += $dy * $dy;
        }

        $denom = sqrt($denX * $denY);

        return $denom == 0 ? 0.0 : round($num / $denom, 2);
    }
}