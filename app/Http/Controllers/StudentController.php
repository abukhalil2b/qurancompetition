<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Question;
use App\Models\StudentQuestionSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all student IDs already in competitions
        $assignedStudentIds = Competition::pluck('student_id')->toArray();

        // List students NOT in competitions
        $students = Student::whereNotIn('id', $assignedStudentIds)
            ->where('gender', $user->gender)
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        if ($user->user_type == 'admin') {
            $students = Student::orderBy('name')
                ->where('active', 1)
                // ->whereNotIn('id', $assignedStudentIds)
                ->get();
        }

        return view('student.index', compact('students'));
    }

    public function presentIndex()
    {
        $user = auth()->user();

        $stage = Stage::where('active', 1)->first();
        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        $committeeUser = CommitteeUser::with('committee.center')
            ->where('user_id', $user->id)
            ->where('stage_id', $stage->id)
            ->first();

        if (!$committeeUser || !$committeeUser->committee || !$committeeUser->committee->center) {
            return back()->with('error', 'المستخدم غير مرتبط بلجنة صالحة');
        }

        $committee = $committeeUser->committee;
        $center    = $committee->center;

        $query = Competition::with([
            'student',
        ])->where([
            'center_id'    => $center->id,
            'committee_id' => $committee->id,
            'stage_id'     => $stage->id,
        ]);

        if ($user->user_type == 'judge') {
            if ($user->isCommitteeLeader($stage->id)) {
                $query->whereIn('student_status', [
                    'with_committee',
                    'present',
                ]);
            } else {
                $query
                    ->whereNotNull('questionset_id')
                    ->whereIn('student_status', [
                        'with_committee',
                        'present',
                    ]);
            }
        } else {
            $query->whereIn('student_status', [
                'with_committee',
                'present',
                'finish_competition',
            ]);
        }

        $competitions = $query->orderby('id', 'ASC')
            ->get();


        return view(
            'student.present_index',
            compact('competitions', 'center', 'stage', 'committee', 'committeeUser')
        );
    }

    public function chooseQuestionset(Competition $competition)
    {
        $judge = auth()->user();

        $stage = Stage::where('active', true)->firstOrFail();

        $committeeUser = CommitteeUser::where([
            'user_id'  => $judge->id,
            'stage_id' => $stage->id,
        ])->firstOrFail();

        $committee = Committee::findOrFail($committeeUser->committee_id);
        $student   = Student::findOrFail($competition->student_id);

        /**
         * CASE 1: Question set already selected
         */
        if ($competition->questionset_id) {

            $questionset = Questionset::findOrFail($competition->questionset_id);

            $studentQuestionSelections = StudentQuestionSelection::with([
                'question',
                'judgeEvaluations',
            ])
                ->where('competition_id', $competition->id)
                ->orderBy('position')
                ->get();

            return view('student.questionset_selected', compact(
                'student',
                'competition',
                'questionset',
                'studentQuestionSelections',
                'stage'
            ));
        }

        /**
         * CASE 2: Choose a new question set
         * Prevent reusing sets already taken by this committee
         */
        $usedQuestionsetIds = Competition::where('committee_id', $committeeUser->committee_id)
            ->whereNotNull('questionset_id')
            ->pluck('questionset_id');

        $questionsets = Questionset::where('level', $student->level)
            ->whereNotIn('id', $usedQuestionsetIds)
            ->withCount('questions')
            ->get();

        return view('student.choose_questionset', compact(
            'questionsets',
            'student',
            'competition'
        ));
    }


    public function saveQuestionset(Competition $competition, Questionset $questionset)
    {
        // 1. Ensure there is an active stage
        $stage = Stage::where('active', 1)->first();

        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        // 2. Prevent re-assigning a questionset
        if ($competition->questionset_id) {
            return back()->with('error', 'تم اختيار مجموعة أسئلة مسبقاً لهذه المسابقة');
        }

        $alreadySelected = Competition::where('questionset_id', $questionset->id)
            ->exists();

        if ($alreadySelected) {
            return back()->with(
                'error',
                'تم استخدام مجموعة الأسئلة هذه مسبقاً في مسابقة أخرى'
            );
        }

        DB::transaction(function () use ($competition, $questionset) {
            // 1. Update the competition
            $competition->update([
                'questionset_id' => $questionset->id,
                'student_status' => 'with_committee'
            ]);

            // 2. Fetch questions from the selected questionset
            $questions = Question::where('questionset_id', $questionset->id)->get();

            // 3. Build selections for each question
            foreach ($questions as $index => $question) {
                StudentQuestionSelection::create([
                    'competition_id' => $competition->id,
                    'question_id'    => $question->id,
                    'level'    => $questionset->level,
                    'position'    => $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('student.choose_questionset', $competition->id)
            ->with('success', 'تم اختيار مجموعة الأسئلة بنجاح');
    }

    // create form
    public function create()
    {
        $levels = ['حفظ', 'حفظ وتفسير'];

        return view('student.create', compact('levels'));
    }

    // store student
    public function store(Request $request)
    {
        $levels = ['حفظ', 'حفظ وتفسير'];

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|size:8|unique:students,phone',
            'national_id'       => 'nullable|string|max:11|unique:students,national_id',
            'nationality'       => 'required|string|max:255',
            'dob'               => 'nullable|date',
            'state'             => 'nullable|string|max:255',
            'wilaya'            => 'nullable|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'level' => ['nullable', 'string', 'max:255', Rule::in($levels)],
            'registration_date' => 'nullable|date',
        ]);

        Student::create($validated);

        return redirect()->route('student.index')->with('success', 'تم إضافة المتسابق بنجاح');
    }

    // show student
    public function show(Student $student)
    {
        $level = $student->level;

        $stage = Stage::where('active', 1)->firstOrFail();

        $questionsets = Questionset::where('level', $level)->get();

        $committees = Committee::select(
            'committees.id as committee_id',
            'committees.title as committee_title',
            'centers.title as center_title'
        )
            ->join('centers', 'centers.id', '=', 'committees.center_id')
            ->get();

        return view('student.show', compact('student', 'stage', 'questionsets', 'level', 'committees'));
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
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|size:8|unique:students,phone,' . $student->id,
            'national_id'       => 'nullable|string|max:11|unique:students,national_id,' . $student->id,
            'nationality'       => 'nullable|string|max:255',
            'dob'               => 'nullable|date',
            'state'             => 'nullable|string|max:255',
            'wilaya'            => 'nullable|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'level'             => 'required|string|max:255',
            'registration_date' => 'nullable|date',
            'active'            => 'required|boolean',
            'note'              => 'nullable|string',
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
