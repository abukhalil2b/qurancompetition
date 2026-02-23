<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use Illuminate\Support\Collection;

class JudgeScoreCalculator
{
    /**
     * Calculate results broken down by judge.
     *
     * @param Competition $competition
     * @return Collection
     */
    public static function calculateStudentResults(Competition $competition): Collection
    {
        // 1. Get all questions with evaluations
        $questions = $competition->studentQuestionSelections()->with([
            'judgeEvaluations.element',
            'judgeEvaluations.judge'
        ])->get();

        // 2. Prepare to aggregate scores
        // Structure: [ judge_id => [ 'name' => '...', 'memorization_score' => 0 ] ]
        $judgeScores = collect();

        // Iterate through each question
        foreach ($questions as $question) {
            // If the question is failed (is_passed == 0), the student gets 0 for this question.
            // This means NO judge gives points for this question.
            if ($question->is_passed == 0) {
                continue;
            }

            // Iterate through evaluations for this question
            foreach ($question->judgeEvaluations as $evaluation) {
                $judgeId = $evaluation->judge_id;
                $judgeName = $evaluation->judge->name ?? 'Unknown Judge';

                if (!$judgeScores->has($judgeId)) {
                    $judgeScores->put($judgeId, [
                        'judge_id' => $judgeId,
                        'judge_name' => $judgeName,
                        'memorization_score' => 0,
                    ]);
                }

                // Calculate the score for this specific evaluation
                // Score = Max Score of Element - Reduction
                $elementMaxScore = $evaluation->element->max_score;
                $score = max(0, $elementMaxScore - $evaluation->reduct_point);

                // Add to judge's total
                $currentData = $judgeScores->get($judgeId);
                $currentData['memorization_score'] += $score;
                $judgeScores->put($judgeId, $currentData);
            }
        }

        // 3. Handle Tafseer if applicable
        $tafseerScore = 0;
        $maxScore = 100;
        if ($competition->student->level === 'حفظ وتفسير') {
            // Use ScoreCalculator service to get the official tafseer score
            $tafseerScore = ScoreCalculator::tafseer($competition);
            $maxScore = 140;
        }

        // 4. Finalize the collection with totals
        return $judgeScores->map(function ($judgeData) use ($tafseerScore, $maxScore) {
            $total = $judgeData['memorization_score'] + $tafseerScore;

            return array_merge($judgeData, [
                'tafseer_score' => $tafseerScore,
                'total_score' => $total,
                'max_score' => $maxScore
            ]);
        })->values(); // Reset keys to be a simple array
    }
}
