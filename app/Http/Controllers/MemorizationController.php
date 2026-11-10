<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\EvaluationElement;
use App\Models\JudgeEvaluation;
use App\Models\JudgeNote;
use App\Models\QuranAya;
use App\Models\Stage;
use App\Models\StudentQuestionSelection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemorizationController extends Controller
{
    public function start($student_question_selection_id)
    {
        $judge = auth()->user();

        $stage = Stage::latest('id')
            ->where('active', 1)
            ->first();

        if (! $stage) {
            abort(403, 'لم يتم تعيين المرحلة');
        }

        $studentQuestionSelection = StudentQuestionSelection::with([
            'competition',
            'question.surat',
            'question.suratTo',
            'judgeEvaluations' => fn ($q) => $q->where('judge_id', $judge->id),
            'judgeNotes' => fn ($q) => $q->where('judge_id', $judge->id),
        ])->findOrFail($student_question_selection_id);

        $competition = $studentQuestionSelection->competition;

        $isJudgeLeader = $judge->isCommitteeLeader($competition->committee_id);

        $question = $studentQuestionSelection->question;

        // سورة البداية والنهاية
        $startSurat = $question->surat;
        $endSurat = $question->suratTo ?? $startSurat;

        if (! $startSurat || ! $endSurat) {
            abort(404, 'بيانات السورة غير مكتملة');
        }

        // جلب جميع الآيات بين السورتين المحددتين
        $quranAyas = QuranAya::query()
            ->join('quran_surats', 'quran_ayas.quran_surat_id', '=', 'quran_surats.id')
            ->whereBetween('quran_surats.number', [
                $startSurat->number,
                $endSurat->number,
            ])
            ->where(function ($query) use ($startSurat, $endSurat, $question) {

                // Case 1: Both Ayahs are in the same Surah
                if ($startSurat->id === $endSurat->id) {

                    $query->where('quran_ayas.quran_surat_id', $startSurat->id)
                        ->whereBetween('quran_ayas.number', [
                            $question->aya_from,
                            $question->aya_to,
                        ]);

                } else {

                    // Starting Surah: from aya_from to the end
                    $query->where(function ($q) use ($startSurat, $question) {
                        $q->where('quran_ayas.quran_surat_id', $startSurat->id)
                            ->where('quran_ayas.number', '>=', $question->aya_from);
                    });

                    // Intermediate Surahs: all Ayahs
                    $query->orWhere(function ($q) use ($startSurat, $endSurat) {
                        $q->where('quran_surats.number', '>', $startSurat->number)
                            ->where('quran_surats.number', '<', $endSurat->number);
                    });

                    // Ending Surah: from the beginning to aya_to
                    $query->orWhere(function ($q) use ($endSurat, $question) {
                        $q->where('quran_ayas.quran_surat_id', $endSurat->id)
                            ->where('quran_ayas.number', '<=', $question->aya_to);
                    });
                }
            })
            ->orderBy('quran_surats.number')
            ->orderBy('quran_ayas.number')
            ->get([
                'quran_ayas.id',
                'quran_ayas.quran_surat_id',
                'quran_ayas.number',
                'quran_ayas.page_number',
                'quran_ayas.content',
                'quran_surats.title as surat_title',
                'quran_surats.number as surat_number',
            ]);

        $firstQuranAya = $quranAyas->first();

        if ($quranAyas->isEmpty()) {
            abort(404, 'لم يتم العثور على آيات السؤال');
        }

        $evaluationElements = EvaluationElement::where('level', $competition->level)->get();

        $oldScores = $studentQuestionSelection
            ->judgeEvaluations
            ->pluck('reduct_point', 'evaluation_element_id');

        $oldNote = $studentQuestionSelection
            ->judgeNotes
            ->first()?->note;

        return view('memorization.start', compact(
            'isJudgeLeader',
            'firstQuranAya',
            'studentQuestionSelection',
            'evaluationElements',
            'stage',
            'oldScores',
            'oldNote',
            'quranAyas',
            'startSurat',
            'endSurat'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_question_selection_id' => 'required|exists:student_question_selections,id',
            'elements' => 'required|array',
            'elements.*' => 'numeric',
            'note' => 'nullable|string',
            'student_lost_question' => 'nullable',
        ]);

        $judge = Auth::user();

        if ($judge->user_type != 'judge') {
            abort(403, 'حسابك غير مصرح له بالتقييم');
        }

        $selection = StudentQuestionSelection::findOrFail($validated['student_question_selection_id']);
        $competition = Competition::findOrFail($selection->competition_id);
        // return $selection;
        // Authorization check
        $isJudge = CommitteeUser::where('committee_id', $competition->committee_id)
            ->where('user_id', $judge->id)
            ->whereRelation('user', 'user_type', 'judge')
            ->exists();

        if (! $isJudge) {
            abort(403, 'أنت لست محكم');
        }

        DB::transaction(function () use ($validated, $selection, $judge, $competition) {
            // Leader logic: Pass/Fail
            if ($judge->isCommitteeLeader($competition->committee_id)) {
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
            ->whereRelation('user', 'user_type', 'judge')
            ->whereNotIn('user_id', $completedIds)
            ->with('user:id,name')->get();
        // return $isQuestionDone;
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
                $buttonText = 'إنهاء وعرض النتيجة';
                $nextUrl = route('result.show', $competition->id);
            }
        }

        // Show navigation list (filtered by current judge)
        $questions = $competition->studentQuestionSelections()->with(['judgeEvaluations'])
            ->whereHas('judgeEvaluations', fn ($q) => $q->where('judge_id', auth()->id()))
            ->orderBy('id')->get();

        return view('memorization.show', compact(
            'questions', 'studentQuestionSelection', 'competition', 'completedJudges',
            'remainingJudges', 'isQuestionDone', 'buttonText', 'nextUrl'
        ));
    }

    protected function checkIfQuestionIsDone(StudentQuestionSelection $selection, Competition $competition)
    {
        if ($selection->done) {
            return true;
        }

        $totalJudges = CommitteeUser::where('committee_id', $competition->committee_id)
            ->whereRelation('user', 'user_type', 'judge')
            ->whereRelation('committee', 'active', 1)
            ->count();

        $evaluatedJudges = JudgeEvaluation::where('student_question_selection_id', $selection->id)->distinct('judge_id')->count();

        if ($evaluatedJudges >= $totalJudges && $totalJudges > 0) {
            $selection->update(['done' => true]);

            return true;
        }

        return false;
    }
}
