<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\TafseerEvaluation;
use App\Models\TafseerQuestion;
use App\Models\TafseerResult;
use Illuminate\Http\Request;

class TafseerController extends Controller
{
    public function start($competitionId)
{
    $competition = Competition::with('student')->findOrFail($competitionId);
    $judge = auth()->user();

    // Load questions
    $questions = TafseerQuestion::orderBy('order')->get();

    // Load existing evaluations for this judge and THESE specific questions
    // keyBy ensures we can access them like: $evaluations[1]->score
    $evaluations = TafseerEvaluation::where('judge_id', $judge->id)
        ->whereIn('tafseer_question_id', $questions->pluck('id'))
        ->get()
        ->keyBy('tafseer_question_id');

    return view('tafseer.start', compact('competition', 'questions', 'evaluations'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|exists:tafseer_questions,id',
            'questions.*.score' => 'required|numeric|min:0',
            'questions.*.note' => 'nullable|string',
        ]);

        $judge = auth()->user();

        foreach ($validated['questions'] as $q) {
            TafseerEvaluation::updateOrCreate(
                [
                    'tafseer_question_id' => $q['question_id'],
                    'judge_id' => $judge->id,
                ],
                [
                    'score' => $q['score'],
                    'note' => $q['note'] ?? null,
                ]
            );
        }

        return redirect()->route('tafseer.start', $request->competition_id)
            ->with('success', 'تم حفظ التقييم بنجاح!');
    }


    protected function checkIfTafseerDone(int $competitionId)
    {
        $totalJudges = CommitteeUser::where(
            'committee_id',
            Competition::find($competitionId)->committee_id
        )->where('role', 'judge')->count();

        $questions = TafseerQuestion::where('competition_id', $competitionId)->get();

        foreach ($questions as $question) {
            if ($question->evaluations()->distinct('judge_id')->count() < $totalJudges) {
                return;
            }
        }

        TafseerResult::updateOrCreate(
            ['competition_id' => $competitionId],
            [
                'total_score' => $this->calculateTotalScore($competitionId),
                'done' => true
            ]
        );
    }

    protected function calculateTotalScore(int $competitionId): float
    {
        return TafseerQuestion::where('competition_id', $competitionId)
            ->get()
            ->sum(function ($question) {
                return $question->evaluations->avg('score');
            });
    }
}
