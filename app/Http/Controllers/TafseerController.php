<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\StudentQuestionSelection;
use App\Models\TafseerEvaluation;
use App\Models\TafseerQuestion;
use App\Models\TafseerResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TafseerController extends Controller
{
    /**
     * Show the bulk grading form for Tafseer.
     */
    public function start(Competition $competition)
    {
        // 1. Check if Memorization is done
        $unfinishedCount = StudentQuestionSelection::where('competition_id', $competition->id)
            ->where('done', false)->count();

        if ($unfinishedCount > 0) {
            $nextQuestion = StudentQuestionSelection::where('competition_id', $competition->id)
                ->where('done', false)->first();
            return redirect()->route('memorization.start', $nextQuestion->id)
                ->with('warning', 'عفواً، يجب الانتهاء من أسئلة الحفظ أولاً قبل الانتقال للتفسير.');
        }

        // 2. Check if Tafseer is ALREADY fully completed (Global)
        // If the result is marked done, no need to wait or grade, go to result.
        $tafseerResult = TafseerResult::where('competition_id', $competition->id)->first();
        if ($tafseerResult && $tafseerResult->done) {
            return redirect()->route('result.show', $competition->id);
        }

        // 3. Prepare Data
        $judge = auth()->user();
        $questions = TafseerQuestion::orderBy('order')->get();

        $evaluations = TafseerEvaluation::where('judge_id', $judge->id)
            ->where('competition_id', $competition->id)
            ->whereIn('tafseer_question_id', $questions->pluck('id'))
            ->get()->keyBy('tafseer_question_id');

        // 4. Determine if THIS judge has finished
        // We assume they are finished if they have evaluated every question.
        $hasFinished = $evaluations->count() >= $questions->count();

        return view('tafseer.start', compact('competition', 'questions', 'evaluations', 'hasFinished'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|exists:tafseer_questions,id',
            'questions.*.score' => 'required|numeric|min:0',
            'questions.*.note' => 'nullable|string',
        ]);

        $judge = auth()->user();
        $competitionId = $validated['competition_id'];

        DB::transaction(function () use ($validated, $judge, $competitionId) {
            foreach ($validated['questions'] as $q) {
                TafseerEvaluation::updateOrCreate(
                    [
                        'competition_id' => $competitionId,
                        'tafseer_question_id' => $q['question_id'],
                        'judge_id' => $judge->id,
                    ],
                    ['score' => $q['score'], 'note' => $q['note'] ?? null]
                );
            }
            $this->checkIfTafseerDone($competitionId);
        });

        // Check completion status
        $tafseerResult = TafseerResult::where('competition_id', $competitionId)->first();

        if ($tafseerResult && $tafseerResult->done) {
            return redirect()->route('result.show', $competitionId)
                ->with('success', 'تم حفظ تقييم التفسير واكتمال النتيجة النهائية!');
        }

        return redirect()->route('tafseer.start', $competitionId)
            ->with('success', 'تم حفظ درجاتك بنجاح. بانتظار باقي المحكمين لاكتمال النتيجة.');
    }

    protected function checkIfTafseerDone(int $competitionId)
    {
        $competition = Competition::find($competitionId);
        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)->where('role', 'judge')->count();
        if ($totalJudges === 0) return;

        $totalQuestions = TafseerQuestion::count();
        $actualEvaluations = TafseerEvaluation::where('competition_id', $competitionId)->count();
        $expectedEvaluations = $totalJudges * $totalQuestions;

        if ($actualEvaluations >= $expectedEvaluations) {
            $finalScore = $this->calculateTotalScore($competitionId);
            TafseerResult::updateOrCreate(
                ['competition_id' => $competitionId],
                ['total_score' => $finalScore, 'done' => true]
            );
        }
    }

    protected function calculateTotalScore(int $competitionId): float
    {
        $questions = TafseerQuestion::all();
        $total = 0;
        foreach ($questions as $question) {
            $total += TafseerEvaluation::where('competition_id', $competitionId)
                ->where('tafseer_question_id', $question->id)->avg('score');
        }
        return $total;
    }
}
