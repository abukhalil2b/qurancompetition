<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Competition;
use App\Services\JudgeScoreCalculator;

class DetailedResultController extends Controller
{
    /**
     * Show the detailed results broken down by judge.
     */
    public function show(Competition $competition)
    {

        $committee = Committee::find($competition->committee_id);

        if (! $committee) {
            abort(403, 'لم يتم تحديد لجنة لهذا المتسابق');
        }

        // 1. Calculate scores per judge
        $judgeScores = JudgeScoreCalculator::calculateStudentResults($competition);

        $judge = auth()->user();

        $isJudgeLeader = $judge->isCommitteeLeader($committee->id);

        // 2. Pass data to the view
        return view('detailed_results.show', [
            'competition' => $competition,
            'student' => $competition->student,
            'judgeScores' => $judgeScores,
            'isJudgeLeader' => $isJudgeLeader,
        ]);
    }
}
