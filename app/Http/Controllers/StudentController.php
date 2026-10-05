<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\Student;
use App\Models\StudentQuestionSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        abort_unless(($user->user_type == 'organizer'), 403, 'هذه الصفحة مخصصة لمنظم المسابقة.');

        // 1. Active stage
        $stage = Stage::where('active', 1)->latest('id')->first();
        abort_unless($stage, 403, 'لا توجد مرحلة نشطة حالياً.');

        // 2. User's active committee for that stage
        $committee = $user->committees()
            ->where('active', 1)
            ->where('stage_id', $stage->id)
            ->latest('id')
            ->first();

        abort_unless($committee, 403, 'لا توجد لجنة نشطة مرتبطة بحسابك في هذه المرحلة.');

        // 3. Center (eager load via committee to avoid extra query)
        $center = $committee->center;

        abort_unless($center, 403, 'اللجنة غير مرتبطة بأي مركز.');

        // 4. Competitions (renamed from $students)
        $competitions = Competition::with('student')
            ->where('stage_id', $stage->id)
            ->whereRelation('committee','center_id', $center->id)
            ->where('committee_id', $committee->id)
            ->get();

            $judges = CommitteeUser::with('user')
           ->where('committee_id',$committee->id)
           ->whereHas('user',function($query){$query->where('users.user_type','judge');})
           ->get();
        return view('student.index', compact(
            'stage',
            'center',
            'committee',
            'competitions',
            'judges'
        ));
    }

    public function presentIndex()
    {
        $user = auth()->user();

        if (! in_array($user->user_type, ['judge', 'admin'])) {
            abort(403, 'غير مصرح دخول هذه الصفحة');
        }

        // 1. Active stage
        $stage = Stage::where('active', 1)->latest('id')->first();
        abort_unless($stage, 403, 'لا توجد مرحلة نشطة حالياً.');

        // 2. User's active committee for that stage
        $committee = $user->committees()
            ->where('active', 1)
            ->where('stage_id', $stage->id)
            ->latest('id')
            ->first();
            
        abort_unless($committee, 403, 'لا توجد لجنة نشطة مرتبطة بحسابك في هذه المرحلة.');

        // 3. Center
        $center = $committee->center;
        abort_unless($center, 403, 'اللجنة غير مرتبطة بأي مركز.');

        // 4. Base query
        $query = Competition::with(['student', 'questionset'])
            ->where('center_id', $center->id)
            ->where('committee_id', $committee->id);

        // 5. Role-based filtering
        if ($user->user_type === 'judge' && ! $user->isCommitteeLeader($committee->id)) {
            // Non-leader judge: only students that have a questionset assigned
            $query->whereNotNull('questionset_id')
                ->whereIn('student_status', ['with_committee', 'present']);
        } else {
            // Leader, admin, or any other role: all active competitions
            $query->whereIn('student_status', [
                'with_committee',
                'present',
                'finish_competition',
            ]);
        }

        $competitions = $query->orderBy('present_at')->get();

        return view('student.present_index', compact(
            'competitions',
            'center',
            'stage',
            'committee'
        ));
    }

    public function chooseQuestionset(Competition $competition)
    {
        $user = auth()->user();

        // ---------------------------------------------------------------------
        // Membership: user must belong to the competition's committee
        // (admins bypass this)
        // ---------------------------------------------------------------------
        $committee = $competition->committee;
        abort_unless($committee, 404, 'اللجنة غير موجودة.');

        $isMember = $user->committees()
            ->where('committees.id', $committee->id)
            ->exists();

        abort_unless(
            $user->user_type === 'admin' || $isMember,
            403,
            'أنت غير مرتبط بهذه اللجنة.'
        );

        // Convenience flag
        $isLeader = $user->user_type === 'admin'
            || $user->isCommitteeLeader($committee->id);

        $stage = Stage::where('active', true)->first();
        abort_unless($stage, 403, 'لا توجد مرحلة نشطة حالياً.');

        $student = $competition->student;

        // ---------------------------------------------------------------------
        // Case A: Questionset already selected → ANY committee member can view
        // ---------------------------------------------------------------------
        if ($competition->questionset_id) {

            $questionset = Questionset::findOrFail($competition->questionset_id);

            $studentQuestionSelections = StudentQuestionSelection::with([
                'question.surat',
                'judgeEvaluations.element',
                'judgeEvaluations.judge',
            ])
                ->where('competition_id', $competition->id)
                ->orderBy('position')
                ->get();

            return view('student.questionset_selected', compact(
                'student',
                'competition',
                'questionset',
                'studentQuestionSelections',
                'stage',
                'isLeader'
            ));
        }

        // ---------------------------------------------------------------------
        // Case B: No questionset yet → ONLY the leader can pick
        // ---------------------------------------------------------------------
        abort_unless(
            $isLeader,
            403,
            'اختيار الباقة متاح لرئيس اللجنة فقط.'
        );

        $usedQuestionsetIds = Competition::where('committee_id', $committee->id)
            ->whereNotNull('questionset_id')
            ->pluck('questionset_id');

        $questionsets = Questionset::where('level', $competition->level)
            ->whereNotIn('id', $usedQuestionsetIds)
            ->where('selected',0)
            ->withCount('questions')
            ->get();

        return view('student.choose_questionset', compact(
            'questionsets',
            'student',
            'competition',
            'stage'
        ));
    }

    public function saveQuestionset(Competition $competition, Questionset $questionset)
    {
        $user = auth()->user();

        // ---------------------------------------------------------------------
        // Authorization
        // ---------------------------------------------------------------------
        abort_unless(
            $user->user_type === 'admin'
                || $user->isCommitteeLeader($competition->committee_id),
            403,
            'اختيار الباقة متاح لرئيس اللجنة فقط.'
        );

        // ---------------------------------------------------------------------
        // Basic guards
        // ---------------------------------------------------------------------
        if ($competition->questionset_id) {
            return back()->with('error', 'تم اختيار باقة أسئلة مسبقاً لهذا المتسابق.');
        }

        if (! in_array($competition->student_status, ['present'], true)) {
            return back()->with('error', 'اختيار الباقة فقط للمتسابق الحاضر، الحالات الأخرى غير مسموح');
        }

        // Questionset must match the competition's level
        if ((int) $questionset->level !== (int) $competition->level) {
            return back()->with('error', 'مستوى الباقة لا تناسب مستوى المتسابق.');
        }

        // ---------------------------------------------------------------------
        // Atomic: re-check "already used" inside the transaction, then assign
        // ---------------------------------------------------------------------
        try {

            DB::transaction(function () use ($competition, $questionset) {

                $freshQuestionset = Questionset::where('id', $questionset->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                //Does any competition other than the current competition already have this questionset?
                $alreadySelected = Competition::where('questionset_id', $freshQuestionset->id)
                    ->where('id', '!=', $competition->id)
                    ->exists();

                if ($alreadySelected) {
                    throw new \RuntimeException('QUESTIONSET_ALREADY_USED');
                }

                $competition->update([
                    'questionset_id' => $freshQuestionset->id,
                    'student_status' => 'with_committee',
                ]);

                $questions = Question::where('questionset_id', $freshQuestionset->id)
                    ->orderBy('id')
                    ->get();

                foreach ($questions as $index => $question) {
                    StudentQuestionSelection::create([
                        'competition_id' => $competition->id,
                        'question_id' => $question->id,
                        'level' => $freshQuestionset->level,   
                        'position' => $index + 1,
                        'is_passed'=>1
                    ]);
                }

                $freshQuestionset->selected = 1;
                $freshQuestionset->save();
            });

        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'QUESTIONSET_ALREADY_USED') {
                return back()->with('error', 'تم استخدام هذه الباقة مسبقاً.');
            }
            throw $e;
        }

        return redirect()
            ->route('student.choose_questionset', $competition->id)
            ->with('success', 'تم اختيار باقة الأسئلة بنجاح.');
    }

    // create form
    public function create()
    {
        $levels = ['المستوى الأول', 'المستوى الثاني'];

        return view('student.create', compact('levels'));
    }

    // store student
    public function store(Request $request)
    {
        $levels = ['المستوى الأول', 'المستوى الثاني'];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|size:8|unique:students,phone',
            'national_id' => 'nullable|string|max:11|unique:students,national_id',
            'nationality' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'state' => 'nullable|string|max:255',
            'wilaya' => 'nullable|string|max:255',
            'qarya' => 'nullable|string|max:255',
            'level' => ['nullable', 'string', 'max:255', Rule::in($levels)],
            'registration_date' => 'nullable|date',
        ]);

        Student::create($validated);

        return redirect()->route('student.index')->with('success', 'تم إضافة المتسابق بنجاح');
    }

    // show student
    public function show(Competition $competition)
    {
        $student = $competition->student;

        $stage = Stage::findOrFail($competition->stage_id);

        $committee = Committee::findOrFail($competition->committee_id);

        return view('student.show', compact(
            'competition',
            'student',
            'stage',
            'committee',
        ));
    }

    // edit student
    public function edit(Student $student)
    {
        return view('student.edit', compact('student'));
    }

    // update student
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|size:8|unique:students,phone,'.$student->id,
            'national_id' => 'nullable|string|max:11|unique:students,national_id,'.$student->id,
            'nationality' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'state' => 'nullable|string|max:255',
            'wilaya' => 'nullable|string|max:255',
            'qarya' => 'nullable|string|max:255',
            'level' => 'required|string|max:255',
            'registration_date' => 'nullable|date',
            'active' => 'required|boolean',
            'note' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('student.index')->with('success', 'تم تحديث بيانات المتسابق بنجاح');
    }

    // delete student
    public function destroy(Student $student)
    {
        return 'حاليا معطل';
        // $student->delete();
        // return back()->with('success', 'تم حذف المتسابق بنجاح');
    }
}
