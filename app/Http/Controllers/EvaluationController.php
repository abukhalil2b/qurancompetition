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
use App\Models\TafseerResult;
use App\Models\User;
use App\Services\ScoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class EvaluationController extends Controller
{
    public function startEvaluation($student_question_selection_id)
    {
        $judge = auth()->user();

        $stage = Stage::latest('id')->where('active', 1)->first();

        if (!$stage) {
            abort(404);
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
        ])
            ->findOrFail($student_question_selection_id);

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
        // 1. Validate the incoming data
        $validated = $request->validate([
            'student_question_selection_id' => 'required|exists:student_question_selections,id',
            'elements' => 'required|array', // The array of scores: [element_id => score]
            'elements.*' => 'numeric',      // Scores like -0.5, -1, 0
            'note' => 'nullable|string',
            'student_lost_question' => 'nullable'
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
            
            if (isset($validated['student_lost_question'])) {
                $selection->is_passed = 0;
            }

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
        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question',
            'judgeEvaluations.element',
            'judgeEvaluations.judge:id,name',
            'judgeNotes.judge:id,name',
        ])->findOrFail($student_question_selection_id);

        $competition = $studentQuestionSelection->competition;

        // Judges who submitted evaluations
        $completedJudgeIds = $studentQuestionSelection->judgeEvaluations
            ->pluck('judge_id')
            ->unique()
            ->values();

        $completedJudges = User::whereIn('id', $completedJudgeIds)->get();

        // Remaining judges
        $remainingJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->whereNotIn('user_id', $completedJudgeIds)
            ->with('user:id,name')
            ->get();

        // Next question (if exists)
        $nextQuestion = StudentQuestionSelection::where('competition_id', $competition->id)
            ->where('done', false)
            ->orderBy('id')
            ->first();

        $buttonText = 'إنهاء الأسئلة';

        if (!$nextQuestion) {
            $buttonText = 'السؤال الآتي';
            if ($competition->student->level ==  'حفظ وتفسير') {
                $buttonText = 'أسئلة التفسير';
            }
        }

        return view('student.show_evaluation', compact(
            'studentQuestionSelection',
            'competition',
            'completedJudges',
            'remainingJudges',
            'nextQuestion',
            'buttonText'
        ));
    }


    public function showFinalResult(Competition $competition)
    {
        $student = $competition->student;

        // 1. Fetch all memorization questions
        $questions = StudentQuestionSelection::with([
            'judgeEvaluations.element'
        ])
            ->where('competition_id', $competition->id)
            ->orderBy('id')
            ->get();

        // 2. If any memorization question is not done → redirect
        $unfinishedQuestion = $questions->firstWhere('done', false);

        if ($unfinishedQuestion) {
            return redirect()->route(
                'student.start_evaluation',
                $unfinishedQuestion->id
            );
        }

        // 3. Memorization score is always calculated
        $scores = ScoreCalculator::final($competition);

        /**
         * ======================================
         * LEVEL: حفظ
         * ======================================
         */
        if ($student->level === 'حفظ') {

            // Tafseer is NOT applicable
            return view('student.final_result', [
                'student'   => $student,
                'questions' => $questions,
                'scores'    => $scores, // max = 100
            ]);
        }

        /**
         * ======================================
         * LEVEL: حفظ وتفسير
         * ======================================
         */
        if ($student->level === 'حفظ وتفسير') {

            $tafseerResult = TafseerResult::where('competition_id', $competition->id)
                ->where('done', true)
                ->first();

            // Tafseer NOT finished yet → redirect
            if (!$tafseerResult) {
                return redirect()->route('tafseer.start', $competition->id);
            }

            // Tafseer finished → show final (out of 140)
            return view('student.final_result', [
                'student'   => $student,
                'questions' => $questions,
                'scores'    => $scores, // max = 140
            ]);
        }

        // Fallback (should never happen)
        abort(500, 'Invalid student level');
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
