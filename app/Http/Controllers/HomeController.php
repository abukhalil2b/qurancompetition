<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\JudgeEvaluation;
use App\Models\QuestionJudgeEvaluation;
use App\Models\Stage;
use App\Models\StudentQuestionSelection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function resetQuestionScore($selectionId)
    {
        return DB::transaction(function () use ($selectionId) {
            // 1. Find the selection
            $selection = StudentQuestionSelection::findOrFail($selectionId);

            // 2. Delete detailed judge evaluations (The cleanup part)
            // This removes the specific element points (e.g., Tajweed: 10, etc.)
            JudgeEvaluation::where('student_question_selection_id', $selectionId)->delete();

            // 3. Update QuestionJudgeEvaluation summary to 0 for all judges
            // Instead of deleting, we set the total to 0 so the judge names still appear in reports
            QuestionJudgeEvaluation::where('student_question_selection_id', $selectionId)
                ->update([
                    'total_question' => 0,
                    'note' => 'تم صفر السؤال بواسطة اللجنة'
                ]);

            // 4. Update the Selection total to 0
            $selection->update(['total_element_evaluation' => 0]);

            // 5. Recalculate Competition Grand Total
            $newGrandTotal = StudentQuestionSelection::where('competition_id', $selection->competition_id)
                ->sum('total_element_evaluation');

            Competition::where('id', $selection->competition_id)->update([
                'grand_total' => $newGrandTotal
            ]);

            return back()->with('success', 'تم تصفير درجات السؤال وتنظيف بيانات التقييم بنجاح');
        });
    }
    public function welcome()
    {
        // return view('report');
        return view('welcome');
    }

    public function dashboard()
    {

        $activeStage = Stage::where('active', 1)->first();

        if (!$activeStage) {
            abort(404);
        }

        $loggedUser = auth()->user();

        if ($loggedUser->user_type == 'judge') {

            // dashboard for judge
            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (!$committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            $readyStudent = Competition::where('committee_id', $committeeUser->committee_id)->first();

            return view('judge_dashboard', compact('readyStudent', 'activeStage'));
        }

        if ($loggedUser->user_type == 'commite') {

            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (!$committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }


            return view('commite_dashboard', compact('activeStage'));
        }


        return view('dashboard', compact('activeStage'));
    }


    public function finalReport($competitionId)
    {
        // Get competition with related data
        $competition = Competition::with([
            'student',
            'center',
            'stage',
            'committee',
            'questionset'
        ])->findOrFail($competitionId);

        // Get all question selections for this competition with evaluations
        $questionSelections = StudentQuestionSelection::where('competition_id', $competitionId)
            ->with([
                'question',
                'judgeEvaluations.element',
                'judgeEvaluations.judge'
            ])
            ->get();

        // Get question judge evaluations (total per question per judge)
        $questionJudgeEvals = QuestionJudgeEvaluation::whereIn(
            'student_question_selection_id',
            $questionSelections->pluck('id')->toArray()
        )->get();

        // Structure data for the view
        $reportData = [];

        foreach ($questionSelections as $selection) {
            // Get all judges who evaluated this question
            $judgeEvaluations = $selection->judgeEvaluations->groupBy('judge_id');

            // Get total scores per judge for this question
            $judgeScores = [];
            foreach ($judgeEvaluations as $judgeId => $evaluations) {
                $questionJudgeEval = $questionJudgeEvals->where('student_question_selection_id', $selection->id)
                    ->where('judge_id', $judgeId)
                    ->first();

                $judgeScores[$judgeId] = [
                    'judge' => $evaluations->first()->judge,
                    'elements' => $evaluations,
                    'total' => $questionJudgeEval ? $questionJudgeEval->total_question : 0,
                    'note' => $questionJudgeEval ? $questionJudgeEval->note : null
                ];
            }

            // Calculate average
            $average = count($judgeScores) > 0
                ? collect($judgeScores)->avg('total')
                : 0;

            $reportData[] = [
                'selection' => $selection,
                'question' => $selection->question,
                'level' => $selection->level,
                'judge_scores' => $judgeScores,
                'average' => round($average, 1),
                'total_element_evaluation' => $selection->total_element_evaluation
            ];
        }

        return view('final_report', [
            'competition' => $competition,
            'student' => $competition->student,
            'reportData' => collect($reportData),
            'judgeCount' => $competition->judge_count,
            'grandTotal' => $competition->grand_total
        ]);
    }

    public function finalResult()
    {
        // Fetch competitions with related student and center data
        $competitions = Competition::with(['student', 'center', 'stage'])
            ->where('student_status', 'finish_competition')
            ->get()
            ->map(function ($competition) {
                // Count how many questions were assigned to this student
                $questionCount = \App\Models\StudentQuestionSelection::where('competition_id', $competition->id)->count();

                // Calculate percentage: Grand Total / (Questions * Judges)
                $divisor = $questionCount * $competition->judge_count;
                $competition->percentage = $divisor > 0 ? ($competition->grand_total / $divisor) : 0;

                return $competition;
            })
            ->sortByDesc('percentage'); // Rank them from highest to lowest

        return view('final_result', compact('competitions'));
    }

    public function finishStudent($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->update(['student_status' => 'finish_competition']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إنهاء مسابقة الطالب بنجاح');
    }

    public function unFinishStudent($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->update(['student_status' => 'with_committee']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إعادة فتح مسابقة الطالب بنجاح');
    }
}
