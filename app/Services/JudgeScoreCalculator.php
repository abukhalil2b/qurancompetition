<?php

namespace App\Services;

use App\Models\Competition;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class JudgeScoreCalculator
{
    /**
     * Calculate the memorization result for each judge.
     *
     * Level 1:
     * - 6 questions
     * - Maximum raw score: 120
     *
     * Level 2:
     * - 5 questions
     * - Maximum raw score: 100
     *
     * A failed question contributes zero points.
     *
     * Each judge's score is calculated independently:
     *
     *     element max score - judge deduction
     *
     * @return Collection<int, array{
     *     judge_id: int,
     *     judge_name: string,
     *     total_score: float,
     *     max_score: float,
     *     percentage: float
     * }>
     *
     * @throws InvalidArgumentException
     */
    public static function calculateStudentResults(Competition $competition): Collection 
    {

        // Get all questions and their judge evaluations.
        $questions = $competition->studentQuestionSelections()
            ->with([
                'judgeEvaluations.element',
                'judgeEvaluations.judge',
            ])
            ->get();

        // Determine the maximum score for this competition level.
        $maxScore = self::maxScore($competition);

        // Store scores grouped by judge.
        $judgeScores = collect();

        foreach ($questions as $question) {

            /*
             * A failed question receives zero points.
             *
             * Therefore, do not add any of its evaluations
             * to the judges' scores.
             */
            if ((int) $question->is_passed === 0) {
                continue;
            }

            foreach ($question->judgeEvaluations as $evaluation) {

                // Ignore evaluations whose element no longer exists.
                if (! $evaluation->element) {
                    continue;
                }

                $judgeId = $evaluation->judge_id;

                $judgeName = $evaluation->judge->name ?? 'محكم';

                $elementMaxScore = (float) $evaluation->element->max_score;

                $deduction = (float) $evaluation->reduct_point;

                /*
                 * Judge's score for this evaluation element:
                 *
                 * Maximum element score - deduction
                 *
                 * Never allow a negative score.
                 */

                $score = max(0,$elementMaxScore - $deduction);//MAX: we don't want a negative score

                // Create the judge record if it does not exist.
                if (! $judgeScores->has($judgeId)) {
                    $judgeScores->put($judgeId, [
                        'judge_id' => $judgeId,
                        'judge_name' => $judgeName,
                        'total_score' => 0,
                    ]);
                }

                // Add this evaluation score to the judge's total.
                $judgeData = $judgeScores->get($judgeId);

                $judgeData['total_score'] += $score;

                $judgeScores->put($judgeId, $judgeData);
            }
        }

        /*
         * Finalize each judge's result.
         */
        return $judgeScores
            ->map(function (array $judgeData) use ($maxScore) {

                $totalScore = round($judgeData['total_score'],2); 
                /*
                 * Normalize the judge's raw score to 100%.
                 *
                 * Level 1:
                 *     score / 120 × 100
                 *
                 * Level 2:
                 *     score / 100 × 100
                 */
                $percentage = ($totalScore / $maxScore) * 100;

                return [
                    'judge_id' => $judgeData['judge_id'],
                    'judge_name' => $judgeData['judge_name'],
                    'total_score' => $totalScore,
                    'max_score' => $maxScore,
                    'percentage' => round($percentage, 2),
                ];
            })
            ->values();
    }

    /**
     * Get the maximum raw score according to the competition level.
     *
     * Level 1 = 120 points
     * Level 2 = 100 points
     *
     * @throws InvalidArgumentException
     */
    private static function maxScore(Competition $competition): float
    {
        return match ((int) $competition->level) {

            1 => 120.0,

            2 => 100.0,

            default => throw new InvalidArgumentException(
                "Invalid competition level: {$competition->level}. ".
                'Expected level 1 or 2.'
            ),
        };
    }
}
