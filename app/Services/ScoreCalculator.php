<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use Illuminate\Support\Collection;

class ScoreCalculator
{
    /**
     * Calculate memorization score (max 100)
     *
     * @param Collection|StudentQuestionSelection[] $questions
     * @return float
     */
    public static function memorization(Collection $questions): float
    {
        return $questions->sum(function (StudentQuestionSelection $selection) {

            // Group evaluations by element
            return $selection->judgeEvaluations
                ->groupBy('evaluation_element_id')
                ->map(function ($evaluations) {

                    $element = $evaluations->first()->element;
                    $maxScore = $element->max_score;

                    // Calculate score per judge
                    $scores = $evaluations->map(function ($evaluation) use ($maxScore) {
                        return $maxScore - $evaluation->reduct_point;
                    });

                    // Average score for this element
                    return $scores->avg();
                })
                ->sum(); // Sum all elements in this question
        });
    }

    /**
     * Calculate tafseer score (max 40)
     *
     * @param Competition $competition
     * @return float
     */
    public static function tafseer(Competition $competition): float
    {
        if (!$competition->tafseerResult || !$competition->tafseerResult->done) {
            return 0;
        }

        return (float) $competition->tafseerResult->total_score;
    }

    /**
     * Calculate final score (100 or 140)
     *
     * @param Competition $competition
     * @return array
     */
    public static function final(Competition $competition): array
    {
        $questions = $competition->studentQuestionSelections()->with([
            'judgeEvaluations.element'
        ])->get();


        $memorizationScore = self::memorization($questions);

        $totalScore = $memorizationScore;
        $maxScore = 100;

        if ($competition->student->level === 'حفظ وتفسير') {
            $tafseerScore = self::tafseer($competition);
            $totalScore += $tafseerScore;
            $maxScore = 140;
        }

        return [
            'memorization' => round($memorizationScore, 2),
            'tafseer'      => round($totalScore - $memorizationScore, 2),
            'total'        => round($totalScore, 2),
            'max'          => $maxScore,
        ];
    }
}
