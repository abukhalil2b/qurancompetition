<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use App\Models\Stage;
use App\Models\EvaluationElement;
use App\Models\JudgeEvaluation;
use App\Models\JudgeNote;
use App\Models\CommitteeUser;
use App\Models\TafseerResult;
use App\Models\TafseerEvaluation;
use App\Services\ScoreCalculator as ServicesScoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function startEvaluation($student_question_selection_id)
    {
        $judge = auth()->user();

        // Ensure we are in an active stage
        $stage = Stage::latest('id')->where('active', 1)->first();
        if (!$stage) {
            abort(404, 'No active stage found');
        }

        $isJudgeLeader = $judge->isCommitteeLeader($stage->id);

        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question',
            'judgeEvaluations' => function ($q) use ($judge) {
                $q->where('judge_id', $judge->id);
            },
            'judgeNotes' => function ($q) use ($judge) {
                $q->where('judge_id', $judge->id);
            },
        ])->findOrFail($student_question_selection_id);

        $evaluationElements = EvaluationElement::all();

        // Old scores (element_id => reduct_point)
        $oldScores = $studentQuestionSelection->judgeEvaluations
            ->pluck('reduct_point', 'evaluation_element_id');

        // Old note (one per judge per question)
        $oldNote = $studentQuestionSelection->judgeNotes
            ->first()?->note;

        return view('student.start_evaluation', compact(
            'isJudgeLeader',
            'studentQuestionSelection',
            'evaluationElements',
            'stage',
            'oldScores',
            'oldNote'
        ));
    }

    public function saveEvaluation(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'student_question_selection_id' => 'required|exists:student_question_selections,id',
            'elements' => 'required|array',
            'elements.*' => 'numeric',
            'note' => 'nullable|string',
            'student_lost_question' => 'nullable'
        ]);

        $judge = Auth::user();
        $selection = StudentQuestionSelection::findOrFail($validated['student_question_selection_id']);
        $competition = Competition::findOrFail($selection->competition_id);

        // 2. Authorization
        $isJudge = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('user_id', $judge->id)
            ->where('role', 'judge')
            ->exists();

        if (!$isJudge) {
            abort(403, 'User is not a judge in this committee.');
        }

        DB::transaction(function () use ($validated, $selection, $judge, $competition) {

            // Mark pass/fail
            if ($judge->isCommitteeLeader($competition->stage_id)) {
                $selection->is_passed = isset($validated['student_lost_question']) ? 0 : 1;
                $selection->save();
            }


            // Save Scores
            foreach ($validated['elements'] as $elementId => $score) {
                $deductionAmount = abs($score);

                JudgeEvaluation::updateOrCreate(
                    [
                        'student_question_selection_id' => $selection->id,
                        'evaluation_element_id' => $elementId,
                        'judge_id' => $judge->id,
                    ],
                    ['reduct_point' => $deductionAmount]
                );
            }

            // Save Notes
            JudgeNote::updateOrCreate(
                [
                    'student_question_selection_id' => $selection->id,
                    'judge_id' => $judge->id,
                ],
                ['note' => $validated['note'] ?? null]
            );

            // Check if this question is finished (all judges submitted)
            $this->checkIfQuestionIsDone($selection, $competition);
        });

        // Redirect to the summary of this question
        return redirect()->route('memorization.show', $selection->id)
            ->with('success', 'تم حفظ التقييم بنجاح');
    }

   

    public function showFinalResult(Competition $competition)
    {
        $student = $competition->student;

        // 1. Ensure all memorization questions are actually marked 'done'
        $unfinishedQuestion = StudentQuestionSelection::where('competition_id', $competition->id)
            ->where('done', false)
            ->orderBy('id')
            ->first();

        if ($unfinishedQuestion) {
            return redirect()->route('memorization.start', $unfinishedQuestion->id)
                ->with('warning', 'يجب إكمال تقييم جميع أسئلة الحفظ أولاً.');
        }

        // 2. Check Tafseer status if applicable
        $tafseerResult = null;
        if ($student->level === 'حفظ وتفسير') {
            $tafseerResult = TafseerResult::where('competition_id', $competition->id)->first();

            // If Tafseer not done/started, redirect them
            if (!$tafseerResult || !$tafseerResult->done) {
                return redirect()->route('tafseer.start', $competition->id)
                    ->with('warning', 'يجب إكمال اختبار التفسير قبل عرض النتيجة النهائية.');
            }
        }

        // 3. Calculate Scores
        $scores = ServicesScoreCalculator::final($competition);

        // Fetch questions for display
        $questions = $competition->studentQuestionSelections()->with([
            'judgeEvaluations.element',
            'judgeEvaluations.judge',
            'question'
        ])->get();

        // if is_passed = 0. then do not caculate score of this

        $judge = auth()->user();

        $isJudgeLeader = $judge->isCommitteeLeader($competition->stage_id);


        return view('student.final_result', [
            'competition' => $competition,
            'student'     => $student,
            'questions'   => $questions,
            'scores'      => $scores,
            'isJudgeLeader'      => $isJudgeLeader,
            'tafseerResult' => $tafseerResult
        ]);
    }

    public function finalizeCompetition(Request $request, Competition $competition)
    {
        // 1. Calculate final numbers one last time to be safe
        $scores = ServicesScoreCalculator::final($competition);

        // 2. Update status AND save the split scores to DB
        $competition->update([
            'student_status'     => 'finish_competition',
            'final_score'        => $scores['total'],       // Total (100 or 140)
            'memorization_score' => $scores['memorization'], // Split score
            'tafseer_score'      => $scores['tafseer'] ?? 0, // Split score
        ]);

        return redirect()->back()->with('success', 'تم اعتماد النتيجة النهائية وإنهاء المسابقة للطالب.');
    }

    // ----------------------------------------------------------------
    // HELPERS
    // ----------------------------------------------------------------

    protected function checkIfQuestionIsDone(StudentQuestionSelection $selection, Competition $competition)
    {
        // If already marked done in DB, return true immediately
        if ($selection->done) {
            return true;
        }

        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->count();

        $evaluatedJudges = JudgeEvaluation::where('student_question_selection_id', $selection->id)
            ->distinct('judge_id')
            ->count('judge_id');

        // If everyone has finished, update DB and return true
        if ($evaluatedJudges >= $totalJudges && $totalJudges > 0) {
            $selection->update(['done' => true]);
            return true;
        }

        // Otherwise, return false
        return false;
    }

    /**
     * Note: This function should be called inside your 'saveTafseerEvaluation' logic
     * wherever that controller is located.
     */
    protected function checkIfTafseerQuestionIsDone(Competition $competition)
    {
        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->count();

        $evaluatedJudges = TafseerEvaluation::where('competition_id', $competition->id)
            ->distinct('judge_id')
            ->count('judge_id');

        if ($evaluatedJudges >= $totalJudges && $totalJudges > 0) {
            TafseerResult::where('competition_id', $competition->id)
                ->update(['done' => true]);
        }
    }
}
