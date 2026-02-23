<?php

namespace App\Exports;

use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\JudgeScoreCalculator;

class CompetitionResultsExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $competitions = Competition::with(['student', 'questionset', 'committee'])
            ->when($this->request->filled('gender'), function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('gender', $this->request->gender);
                });
            })
            ->when($this->request->filled('level'), function ($query) {
                $query->where('level', $this->request->level);
            })
            ->whereNotNull('final_score')
            ->orderByDesc('final_score')
            ->get();

        // Enrich with detailed judge scores
        foreach ($competitions as $competition) {
            $competition->detailedScores = JudgeScoreCalculator::calculateStudentResults($competition);
        }

        // Collect all judges used in these competitions (unique)
        $judges = collect();

        foreach ($competitions as $competition) {
            foreach ($competition->detailedScores as $judgeScore) {
                $judges->put($judgeScore['judge_id'], [
                    'judge_id'   => $judgeScore['judge_id'],
                    'judge_name' => $judgeScore['judge_name'],
                ]);
            }
        }

        // Calculate max judges used across these results to determine colspan
        $maxJudges = $competitions->map(fn($c) => $c->detailedScores->count())->max() ?? 3;
        $maxJudges = max($maxJudges, 3); // Ensure at least 3 for layout

        return view('exports.results', [
            'competitions' => $competitions,
            'judges'       => $judges->values(),
            'maxJudges'    => $maxJudges,
            'title'        => 'نتائج التصفيات النهائية لمسابقة فاستمسك ١٤٤٧هـ / ٢٠٢٦م'
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '333333']]], // Main Title
            2 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EFEFEF']]], // Header Row 1
            3 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EFEFEF']]], // Header Row 2
        ];
    }
}
