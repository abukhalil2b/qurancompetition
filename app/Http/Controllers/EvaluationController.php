<?php

namespace App\Http\Controllers;

use App\Models\JudgeEvaluation;
use App\Models\StudentQuestionSelection;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\EvaluationElement;
use App\Models\JudgeNote;
use App\Models\Stage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class EvaluationController extends Controller
{
    public function startEvaluation($student_question_selection_id)
    {
        $judge = auth()->user();

        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question',
            'judgeEvaluations' => function ($q) use ($judge) {
                $q->where('judge_id', $judge->id);
            },
            'judgeNotes' => function ($q) use ($judge) {
                $q->where('judge_id', $judge->id);
            },
        ])
            ->findOrFail($student_question_selection_id);

        $evaluationElements = EvaluationElement::where(
            'level',
            $studentQuestionSelection->level
        )->get();

        // Old scores (element_id => reduct_point)
        $oldScores = $studentQuestionSelection->judgeEvaluations
            ->pluck('reduct_point', 'evaluation_element_id');

        // Old note (one per judge per question)
        $oldNote = $studentQuestionSelection->judgeNotes
            ->first()?->note;

        return view('student.start_evaluation', compact(
            'studentQuestionSelection',
            'evaluationElements',
            'oldScores',
            'oldNote'
        ));
    }


    public function saveEvaluation(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'student_question_selection_id' => 'required|exists:student_question_selections,id',
            'elements' => 'required|array', // The array of scores: [element_id => score]
            'elements.*' => 'numeric',      // Scores like -0.5, -1, 0
            'note' => 'nullable|string',
        ]);

        $judge = Auth::user();
        $selection = StudentQuestionSelection::findOrFail($validated['student_question_selection_id']);
        $competition = Competition::findOrFail($selection->competition_id);

        // 2. Authorization: Ensure the User is a Judge in this Committee
        $isJudge = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('user_id', $judge->id)
            ->where('role', 'judge')
            ->exists();

        if (!$isJudge) {
            abort(403, 'User is not a judge in this committee.');
        }

        // 3. Store Data Transactionally
        DB::transaction(function () use ($validated, $selection, $judge, $competition) {

            // Loop through the evaluation elements submitted from the form
            foreach ($validated['elements'] as $elementId => $score) {
                $deductionAmount = abs($score);

                JudgeEvaluation::updateOrCreate(
                    [
                        'student_question_selection_id' => $selection->id,
                        'evaluation_element_id' => $elementId,
                        'judge_id' => $judge->id,
                    ],
                    [
                        'reduct_point' => $deductionAmount,
                    ]
                );

                // Store note for this element (if provided)
                JudgeNote::updateOrCreate(
                    [
                        'student_question_selection_id' => $selection->id,
                        'judge_id' => $judge->id,
                    ],
                    [
                        'note' => $validated['note'] ?? null,
                    ]
                );
            }

            // 4. Check if we should move to the next question
            $this->checkIfQuestionIsDone($selection, $competition);
        });

        return redirect()->route('student.show_evaluation', $selection->id)
            ->with('success', 'تم حفظ التقييم بنجاح');
    }


    public function showEvaluation($student_question_selection_id)
    {
        $judge = auth()->user();

        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question',
            'judgeEvaluations' => function ($q) use ($judge) {
                $q->where('judge_id', $judge->id)
                    ->with('element');
            },
        ])->findOrFail($student_question_selection_id);

        $competition = $studentQuestionSelection->competition;

        // Total judges in this committee
        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->count();

        // Completed judges: user IDs who have submitted at least one score
        $completedJudgeIds = JudgeEvaluation::where('student_question_selection_id', $studentQuestionSelection->id)
            ->pluck('judge_id')
            ->unique();

        // Fetch User models for completed judges
        $completedJudges = User::whereIn('id', $completedJudgeIds)->get();

        // Remaining judges: those who haven’t submitted
        $remainingJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->whereNotIn('user_id', $completedJudgeIds)
            ->with('user:id,name')
            ->get();

        // Mark question done if all judges finished
        if ($completedJudges->count() >= $totalJudges && $totalJudges > 0) {
            $studentQuestionSelection->update(['done' => true]);
        }

        return view('student.show_evaluation', compact(
            'studentQuestionSelection',
            'totalJudges',
            'completedJudges', // now available in Blade
            'remainingJudges'
        ));
    }





    public function evaluationStatus($id)
    {
        $selection = StudentQuestionSelection::select('done')
            ->findOrFail($id);

        return response()->json([
            'done' => $selection->done,
        ]);
    }

    public function showFinalResult(Competition $competition)
{
    $questions = StudentQuestionSelection::with([
        'question',
        'judgeEvaluations.element',
        'judgeNotes.judge',
    ])
    ->where('competition_id', $competition->id)
    ->get();

    $student = Student::find($competition->student_id);

    // Check if any question is not done yet
    $nextQuestion = $questions->firstWhere('done', false);
    if ($nextQuestion) {
        return redirect()->route('student.start_evaluation', $nextQuestion->id);
    }

    return view('student.final_result', compact('student', 'questions'));
}




    protected function checkIfQuestionIsDone(StudentQuestionSelection $selection, Competition $competition)
    {
        // A. Count total judges assigned to this committee
        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge') // Assuming 'judge' is the role key
            ->count();

        // B. Count unique judges who have submitted evaluations for this question
        $evaluatedJudges = JudgeEvaluation::where('student_question_selection_id', $selection->id)
            ->distinct('judge_id')
            ->count('judge_id');

        // C. Compare
        if ($evaluatedJudges >= $totalJudges && $totalJudges > 0) {
            $selection->update(['done' => true]);
        }
    }
}
