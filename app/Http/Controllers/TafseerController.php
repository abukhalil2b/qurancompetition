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
        // Load competition
        $competition = \App\Models\Competition::with('student')->findOrFail($competitionId);

        // Load tafseer questions
        $questions = \App\Models\TafseerQuestion::where('competition_id', $competitionId)
            ->orderBy('order')
            ->get();

        return view('tafseer.start', compact('competition', 'questions'));
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
        \App\Models\TafseerEvaluation::updateOrCreate(
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
        ->with('success', 'Evaluation saved successfully!');
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
