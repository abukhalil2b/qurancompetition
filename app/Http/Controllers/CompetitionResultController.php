<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use App\Models\TafseerResult;
use App\Services\ScoreCalculator; // Ensure this import is correct
use Illuminate\Http\Request;

class CompetitionResultController extends Controller
{
    /**
     * Display the list of all finished students (Honor Board).
     * Moved from HomeController.
     */
    public function index()
    {
        // 1. Memorization List: All finished students, ordered by Memorization Score
        $memorizationList = Competition::with(['student', 'center'])
            ->where('student_status', 'finish_competition')
            ->orderByDesc('memorization_score')
            ->get();

        // 2. Tafseer List: Only students who participated in Tafseer
        $tafseerList = Competition::with(['student', 'center'])
            ->where('student_status', 'finish_competition')
            ->orderByDesc('tafseer_score')
            ->get();

        return view('finished_student_list', compact('memorizationList', 'tafseerList'));
    }

    /**
     * Show the individual Final Result Certificate/Page.
     */
    public function show($competitionId)
    {
        $competition = Competition::with('student')->findOrFail($competitionId);
        $student = $competition->student;

        // Guards: Ensure questions are done before showing result
        $unfinished = StudentQuestionSelection::where('competition_id', $competitionId)
            ->where('done', false)->orderBy('id')->first();

        if ($unfinished) {
            return redirect()->route('memorization.start', $unfinished->id)
                ->with('warning', 'يجب إكمال الحفظ أولاً.');
        }

        if ($student->level === 'حفظ وتفسير') {
            $tafseerResult = TafseerResult::where('competition_id', $competitionId)->first();
            if (!$tafseerResult || !$tafseerResult->done) {
                return redirect()->route('tafseer.start', $competitionId)
                    ->with('warning', 'يجب إكمال التفسير أولاً.');
            }
        }

        $scores = ScoreCalculator::final($competition);

        $questions = $competition->studentQuestionSelections()->with([
            'judgeEvaluations.element',
            'judgeEvaluations.judge',
            'question'
        ])->get();

        $judge = auth()->user();
        $isJudgeLeader = $judge->isCommitteeLeader($competition->stage_id);
        $tafseerResult = TafseerResult::where('competition_id', $competitionId)->first();

        return view('student.final_result', [
            'competition'   => $competition,
            'student'       => $student,
            'questions'     => $questions,
            'scores'        => $scores,
            'isJudgeLeader' => $isJudgeLeader,
            'tafseerResult' => $tafseerResult
        ]);
    }

    /**
     * Lock the competition and save final scores.
     */
    public function finalize(Request $request, Competition $competition)
    {
        $scores = ScoreCalculator::final($competition);

        $competition->update([
            'student_status'     => 'finish_competition',
            'final_score'        => $scores['total'],
            'memorization_score' => $scores['memorization'],
            'tafseer_score'      => $scores['tafseer'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'تم اعتماد النتيجة النهائية.');
    }


    public function unFinishStudent(Competition $competition)
    {
        $competition->update(['student_status' => 'with_committee']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إعادة فتح مسابقة الطالب بنجاح');
    }
}
