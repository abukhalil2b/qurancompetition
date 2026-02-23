<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Services\JudgeScoreCalculator;
use Illuminate\Http\Request;

class DetailedResultController extends Controller
{
    /**
     * Show the detailed results broken down by judge.
     */
    public function show(Competition $competition)
    {
        // 1. Calculate scores per judge
        $judgeScores = JudgeScoreCalculator::calculateStudentResults($competition);

        $judge = auth()->user();
        $isJudgeLeader = $judge->isCommitteeLeader($competition->stage_id);

        // 2. Pass data to the view
        return view('detailed_results.show', [
            'competition' => $competition,
            'student' => $competition->student,
            'judgeScores' => $judgeScores,
            'isJudgeLeader' => $isJudgeLeader,
        ]);
    }
}
