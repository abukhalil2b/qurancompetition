<?php

namespace App\Http\Controllers;

use App\Exports\CompetitionResultsExport;
use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition; // Ensure this import is correct
use App\Models\StudentQuestionSelection;
use App\Services\ScoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CompetitionResultController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (! in_array($user->user_type, ['admin'])) {
            abort(403, 'غير مصرح دخول هذه الصفحة');
        }

        // Handle Excel export with dynamic filename
        if ($request->has('export')) {
            $filename = $this->generateExportFilename($request);

            return Excel::download(new CompetitionResultsExport($request), $filename);
        }

        $competitions = Competition::with(['student', 'questionset', 'center'])
            ->when($request->filled('center_id'), function ($query) use ($request) {
                $query->where('center_id', $request->center_id);
            })
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('gender', $request->gender);
                });
            })
            ->when($request->filled('level'), function ($query) use ($request) {
                $query->where('level', $request->level);
            })
            ->whereNotNull('final_score')
            ->orderByDesc('final_score')
            ->get();

        $centers = Center::all();

        return view('finished_student_list', compact('competitions', 'centers'));
    }

    /**
     * Generate descriptive filename reflecting active filters.
     */
    protected function generateExportFilename(Request $request): string
    {
        $parts = ['نتائج_المسابقة'];

        // Add Center name if filtered
        if ($request->filled('center_id')) {
            $center = Center::find($request->center_id);
            if ($center) {
                $parts[] = Str::slug($center->title, '_', null);
            }
        }

        // Add Level
        if ($request->filled('level')) {
            $parts[] = ((int) $request->level === 1) ? 'المستوى_الأول' : 'المستوى_الثاني';
        }

        // Add Gender
        if ($request->filled('gender')) {
            $parts[] = ($request->gender === 'male') ? 'ذكور' : 'إناث';
        }

        // Add Date timestamp
        $parts[] = date('Y-m-d');

        // Clean double underscores or trailing separators
        $cleanName = implode('_', array_filter($parts));

        return $cleanName.'.xlsx';
    }

    /**
     * Show the individual Final Result Certificate/Page.
     */
    public function show($competitionId)
    {

        $user = auth()->user();

        if (! in_array($user->user_type, ['judge', 'admin'])) {
            abort(403, 'غير مصرح لك الدخول لهذه الصفحة');
        }

        $competition = Competition::with('student')->findOrFail($competitionId);

        $committee = Committee::find($competition->committee_id);

        if (! $committee) {
            abort(403, 'لم يتم تحديد لجنة لهذا المتسابق');
        }

        $student = $competition->student;
        $level = $competition->level;

        // Guards: Ensure questions are done before showing result
        $unfinished = StudentQuestionSelection::where('competition_id', $competitionId)
            ->where('done', false)->orderBy('id')->first();

        if ($unfinished) {
            return back()->with('warning', 'يجب إكمال جميع الأسئلة.');
        }

        $scores = ScoreCalculator::final($competition);

        $questions = $competition->studentQuestionSelections()
            ->with([
                'judgeEvaluations.element',
                'judgeEvaluations.judge',
                'question',
            ])
            ->get();

        $judge = auth()->user();

        $isJudgeLeader = $judge->isCommitteeLeader($committee->id);

        return view('student.final_result', [
            'level' => $level,
            'competition' => $competition,
            'student' => $student,
            'questions' => $questions,
            'scores' => $scores,
            'isJudgeLeader' => $isJudgeLeader,
        ]);
    }

    /**
     * Lock the competition and save final scores.
     */
    public function finalize(Request $request, Competition $competition)
    {
        $user = auth()->user();

        if (! in_array($user->user_type, ['judge'])) {
            abort(403, 'غير مصرح لك اعتماد النتيجة النهائية');
        }

        $scores = ScoreCalculator::final($competition);

        $competition->update([
            'student_status' => 'finish_competition',
            'final_score' => $scores['total'],
        ]);

        return redirect()->back()->with('success', 'تم اعتماد النتيجة النهائية.');
    }

    public function unFinishStudent(Competition $competition)
    {
        $competition->update(['student_status' => 'with_committee']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إعادة فتح مسابقة المتسابق بنجاح');
    }
}
