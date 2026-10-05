<?php
namespace App\Exports;

use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompetitionResultsExport implements FromView, WithColumnWidths, WithStyles
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $competitions = Competition::with([
            'student',
            'questionset',
            'center',
            'committee',
            'studentQuestionSelections.judgeEvaluations.element',
            'studentQuestionSelections.judgeEvaluations.judge',
        ])
            ->when($this->request->filled('center_id'), function ($query) {
                $query->where('center_id', $this->request->center_id);
            })
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

        foreach ($competitions as $competition) {
            $competition->detailedScores = $this->calculateJudgeScores($competition);
        }

        $judges = collect();
        foreach ($competitions as $competition) {
            foreach ($competition->detailedScores as $judgeScore) {
                $judges->put($judgeScore['judge_id'], [
                    'judge_id'   => $judgeScore['judge_id'],
                    'judge_name' => $judgeScore['judge_name'],
                ]);
            }
        }

        $maxJudges = max($judges->count(), 1);

        return view('exports.results', [
            'competitions' => $competitions,
            'judges'       => $judges->values(),
            'maxJudges'    => $maxJudges,
            'title'        => 'نتائج التصفيات لمسابقة القرآن الكريم',
        ]);
    }

    protected function calculateJudgeScores(Competition $competition): Collection
    {
        $selections = $competition->studentQuestionSelections;
        $allEvaluations = $selections->pluck('judgeEvaluations')->collapse();
        $judges = $allEvaluations->pluck('judge')->filter()->unique('id');

        $judgeScores = collect();

        foreach ($judges as $judge) {
            $judgeTotal = 0;

            foreach ($selections as $selection) {
                if ((int) $selection->is_passed === 0) {
                    continue;
                }

                $evaluations = $selection->judgeEvaluations->where('judge_id', $judge->id);

                foreach ($evaluations as $eval) {
                    $maxScore = (float) ($eval->element->max_score ?? 0);
                    $reductPoint = (float) ($eval->reduct_point ?? 0);
                    $judgeTotal += max(0, $maxScore - $reductPoint);
                }
            }

            $judgeScores->push([
                'judge_id'   => $judge->id,
                'judge_name' => $judge->name,
                'total'      => round($judgeTotal, 2),
            ]);
        }

        return $judgeScores;
    }

    /**
     * Define explicit, safe column widths to prevent cell squishing/crashing in RTL layout.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 32,  // Student Name
            'C' => 25,  // Center Title
            'D' => 12,  // Gender
            'E' => 25,  // Question Set
            'F' => 18,  // Level
            'G' => 20,  // Judge 1
            'H' => 20,  // Judge 2
            'I' => 20,  // Judge 3
            'J' => 18,  // Final Score
            'K' => 18,  // Percentage
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $fullRange = "A1:{$highestColumn}{$highestRow}";
        $dataRange = "A2:{$highestColumn}{$highestRow}";

        // Fixed Row Heights
        $sheet->getRowDimension(1)->setRowHeight(45); // Title Header
        $sheet->getRowDimension(2)->setRowHeight(32); // Table Headers

        for ($row = 3; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(28); // Data rows
        }

        // Alignments and text wrapping
        $sheet->getStyle($fullRange)->applyFromArray([
            'alignment' => [
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true, // Prevents text crashing out of cell bounds
            ],
        ]);

        // Cell borders
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '111827']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}