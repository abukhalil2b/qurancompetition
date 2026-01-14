<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use App\Models\Stage;
use App\Models\EvaluationElement;
use App\Models\JudgeEvaluation;
use App\Models\JudgeNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemorizationController extends Controller
{
    /**
     * Show the grading form for a specific memorization question.
     */
    public function start($student_question_selection_id)
    {
        $judge = auth()->user();

        $stage = Stage::latest('id')->where('active', 1)->firstOrFail();

        $isJudgeLeader = $judge->isCommitteeLeader($stage->id);

        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question',
            'judgeEvaluations' => fn($q) => $q->where('judge_id', $judge->id),
            'judgeNotes'       => fn($q) => $q->where('judge_id', $judge->id),
        ])->findOrFail($student_question_selection_id);

        $evaluationElements = EvaluationElement::all();

        $oldScores = $studentQuestionSelection->judgeEvaluations->pluck('reduct_point', 'evaluation_element_id');

        $oldNote = $studentQuestionSelection->judgeNotes->first()?->note;

        return view('memorization.start', compact('isJudgeLeader', 'studentQuestionSelection', 'evaluationElements', 'stage', 'oldScores', 'oldNote'));
    }

    /**
     * Save the scores for a single question.
     */
    public function store(Request $request)
    {
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

        // Authorization check
        $isJudge = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('user_id', $judge->id)
            ->where('role', 'judge')
            ->exists();

        if (!$isJudge) abort(403);

        DB::transaction(function () use ($validated, $selection, $judge, $competition) {
            // Leader logic: Pass/Fail
            if ($judge->isCommitteeLeader($competition->stage_id)) {
                $selection->is_passed = isset($validated['student_lost_question']) ? 0 : 1;
                $selection->save();
            }

            // Save Scores
            foreach ($validated['elements'] as $elementId => $score) {
                JudgeEvaluation::updateOrCreate(
                    [
                        'student_question_selection_id' => $selection->id,
                        'evaluation_element_id' => $elementId,
                        'judge_id' => $judge->id,
                    ],
                    ['reduct_point' => abs($score)]
                );
            }

            // Save Note
            JudgeNote::updateOrCreate(
                ['student_question_selection_id' => $selection->id, 'judge_id' => $judge->id],
                ['note' => $validated['note'] ?? null]
            );

            // Sync check
            $this->checkIfQuestionIsDone($selection, $competition);
        });

        return redirect()->route('memorization.show', $selection->id)
            ->with('success', 'تم حفظ التقييم بنجاح');
    }

    /**
     * Show the summary/waiting room for a specific question.
     */
    public function show($student_question_selection_id)
    {
        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition.student', 'question', 'judgeEvaluations.element',
            'judgeEvaluations.judge:id,name', 'judgeNotes.judge:id,name',
        ])->findOrFail($student_question_selection_id);

        $competition = $studentQuestionSelection->competition;
        $isQuestionDone = $this->checkIfQuestionIsDone($studentQuestionSelection, $competition);

        // Judge Status Logic
        $completedIds = $studentQuestionSelection->judgeEvaluations->pluck('judge_id')->unique();
        $completedJudges = User::whereIn('id', $completedIds)->get();
        $remainingJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('role', 'judge')
            ->whereNotIn('user_id', $completedIds)
            ->with('user:id,name')->get();

        // Next Step Logic
        $nextUrl = null;
        $buttonText = 'في انتظار باقي المحكمين...';

        if ($isQuestionDone) {
            $nextQuestion = StudentQuestionSelection::where('competition_id', $competition->id)
                ->where('done', false)
                ->where('id', '>', $studentQuestionSelection->id)
                ->orderBy('id')
                ->first();

            if ($nextQuestion) {
                $buttonText = 'السؤال الآتي';
                $nextUrl = route('memorization.start', $nextQuestion->id);
            } else {
                // Done with Memorization -> Check Level
                if ($competition->student->level === 'حفظ وتفسير') {
                    $buttonText = 'أسئلة التفسير';
                    $nextUrl = route('tafseer.start', $competition->id);
                } else {
                    $buttonText = 'إنهاء وعرض النتيجة';
                    $nextUrl = route('result.show', $competition->id);
                }
            }
        }

        // Show navigation list (filtered by current judge)
        $questions = $competition->studentQuestionSelections()->with(['judgeEvaluations'])
            ->whereHas('judgeEvaluations', fn($q) => $q->where('judge_id', auth()->id()))
            ->orderBy('id')->get();

        return view('memorization.show', compact(
            'questions', 'studentQuestionSelection', 'competition', 'completedJudges', 
            'remainingJudges', 'isQuestionDone', 'buttonText', 'nextUrl'
        ));
    }

    protected function checkIfQuestionIsDone(StudentQuestionSelection $selection, Competition $competition)
    {
        if ($selection->done) return true;

        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)->where('role', 'judge')->count();
        $evaluatedJudges = JudgeEvaluation::where('student_question_selection_id', $selection->id)->distinct('judge_id')->count();

        if ($evaluatedJudges >= $totalJudges && $totalJudges > 0) {
            $selection->update(['done' => true]);
            return true;
        }
        return false;
    }
}