<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ScoreCalculator
{
    /**
     * Calculate the memorization score.
     *
     * Level 1:
     * - 6 questions
     * - 20 points per question
     * - Maximum = 120
     *
     * Level 2:
     * - 5 questions
     * - 20 points per question
     * - Maximum = 100
     *
     * A failed question (is_passed = 0) receives zero points.
     *
     * For passed questions:
     * - Group evaluations by evaluation element.
     * - Calculate each judge's score:
     *      max_score - reduct_point
     * - Average the judges' scores for each element.
     * - Sum all elements for the question.
     *
     * @param  Collection<int, StudentQuestionSelection>  $questions
     */
    public static function memorization(Collection $questions): float
    {
        return $questions->sum(function (StudentQuestionSelection $selection) {

            // A failed question receives zero points.
            if ((int) $selection->is_passed === 0) {
                return 0;
            }

            // Calculate the score for this passed question.
            return $selection->judgeEvaluations
                ->groupBy('evaluation_element_id')
                ->map(function (Collection $evaluations) {

                    $element = $evaluations->first()->element;

                    if (! $element) {
                        return 0;
                    }

                    $maxScore = (float) $element->max_score;

                    // Calculate each judge's actual score.
                    $scores = $evaluations->map(function ($evaluation) use ($maxScore) {
                        return max(
                            0,
                            $maxScore - (float) $evaluation->reduct_point
                        );
                    });

                    // Average the judges' scores for this element.
                    return $scores->avg() ?? 0;
                })
                ->sum();
        });
    }

    /**
     * Get the maximum raw score for the competition level.
     *
     * Level 1 = 120 points
     * Level 2 = 100 points
     *
     *
     * @throws InvalidArgumentException
     */
    public static function maxScore(Competition $competition): float
    {
        return match ((int) $competition->level) {
            1 => 120.0,
            2 => 100.0,
            default => throw new InvalidArgumentException(
                "Invalid competition level: {$competition->level}. Expected level 1 or 2."
            ),
        };
    }

    /**
     * Calculate the final competition result.
     *
     * The competition now has only one score:
     * memorization.
     *
     * Level 1:
     *     raw score / 120 × 100
     *
     * Level 2:
     *     raw score / 100 × 100
     *
     * @return array{
     *     memorization: float,
     *     total: float,
     *     max: float,
     *     percentage: float
     * }
     *
     * @throws InvalidArgumentException
     */
    public static function final(Competition $competition): array
    {
        $questions = $competition->studentQuestionSelections()
            ->with([
                'judgeEvaluations.element',
            ])
            ->get();

        $memorizationScore = self::memorization($questions);

        // This also validates the competition level.
        $maxScore = self::maxScore($competition);

        // Normalize the raw score to 100%.
        $percentage = ($memorizationScore / $maxScore) * 100;

        return [
            'total' => round($memorizationScore, 2),
            'max' => $maxScore,
            'percentage' => round($percentage, 2),
        ];
    }
}
