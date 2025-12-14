<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Center;
use App\Models\EvaluationElement;
use App\Models\JudgeEvaluation;
use App\Models\Question;
use App\Models\QuestionJudgeEvaluation;
use App\Models\StudentQuestionSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        // Get all student IDs already in competitions
        $assignedStudentIds = Competition::pluck('student_id')->toArray();

        // List students NOT in competitions
        $students = Student::whereNotIn('id', $assignedStudentIds)
            ->orderBy('name')
            ->get();

        return view('student.index', compact('students'));
    }


    public function presentIndex()
    {
        $loggedUser = auth()->user();

        $stage = Stage::where('active', 1)->first();

        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        $committeeUser = CommitteeUser::where('user_id', $loggedUser->id)->where('stage_id', $stage->id)->first();

        if (!$committeeUser) {
            return back()->with('error', 'المستخدم غير مرتبط باللجنة');
        }

        $committee = Committee::findOrFail($committeeUser->committee_id);

        $center = Center::findOrFail($committee->center_id);

        $competitions = Competition::select(
            'competitions.*',
            'students.name as student_name',
            'students.gender',
            'students.nationality',
            'students.national_id',
            'students.level',
            'competitions.created_at as present_at'
        )
            ->leftJoin('students', 'competitions.student_id', '=', 'students.id')
            ->with('questionset.questions')
            ->where('competitions.center_id', $center->id)
            ->where('competitions.committee_id', $committee->id)
            ->where('competitions.stage_id', $stage->id)
            ->get();


        return view('student.present_index', compact('competitions', 'center', 'stage', 'committee'));
    }

    // create form
    public function chooseQuestionset(Competition $competition)
    {
        $judge = auth()->user();

        $stage = Stage::where('active', 1)->first();

        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        $committeeUser = CommitteeUser::where('user_id', $judge->id)->where('stage_id', $stage->id)->first();

        if (!$committeeUser) {
            return back()->with('error', 'المستخدم غير مرتبط باللجنة');
        }

        $committee = Committee::findOrFail($committeeUser->committee_id);

        $center = Center::findOrFail($committee->center_id);


        $student = Student::findOrFail($competition->student_id);

        if ($competition->questionset_id) {

            $evaluationElementCount = EvaluationElement::where('level', $student->level)->count();

            $questionset = Questionset::findOrFail($competition->questionset_id);

            $studentQuestionSelections = StudentQuestionSelection::with(['question'])
                ->withCount([
                    'judgeEvaluations as unique_judge_count' => function ($query) use ($judge) {
                        $query->where('judge_id', $judge->id);
                    }
                ])
                ->where('competition_id', $competition->id)
                ->get();

            $studentQuestionSelectionIds = $studentQuestionSelections->pluck('id');

            $judgeEvaluation =  JudgeEvaluation::whereIn('student_question_selection_id', $studentQuestionSelectionIds)->get();

            return view('student.questionset_selected', compact('student', 'studentQuestionSelections', 'questionset', 'evaluationElementCount'));
        }

        $questionsetIds = Competition::where('committee_id', $committeeUser->committee_id)
            ->whereNotNull('questionset_id')
            ->pluck('questionset_id');

        $questionsets = Questionset::where('level', $student->level)
            ->withCount('questions')
            ->whereNotIn('id', $questionsetIds)
            ->get();

        return view('student.choose_questionset', compact('questionsets', 'student', 'competition'));
    }


    public function saveQuestionset(Competition $competition, Questionset $questionset)
    {
        $stage = Stage::where('active', 1)->first();

        Competition::where(['questionset_id' => $questionset->id, 'committee_id' => 1])->first();

        // 1. Update the competition
        $competition->update([
            'questionset_id' => $questionset->id,
            'student_status' => 'with_committee'
        ]);

        // 2. Fetch questions from the selected questionset
        $questions = Question::where('questionset_id', $questionset->id)->get();

        // 3. Build selections for each question
        foreach ($questions as $question) {
            StudentQuestionSelection::create([
                'center_id' => $competition->center_id,
                'stage_id' => $competition->stage_id,
                'committee_id' => $competition->committee_id,
                'competition_id' => $competition->id,
                'questionset_id' => $questionset->id,
                'question_id'    => $question->id,
                'level'    => $questionset->level,
                'student_id'     => $competition->student_id
            ]);
        }

        return redirect()
            ->route('student.choose_questionset', $competition->id)
            ->with('success', 'تم اختيار مجموعة الأسئلة بنجاح');
    }

    public function startEvaluation($student_question_selection_id)
    {

        $studentQuestionSelection = StudentQuestionSelection::with(['student', 'center', 'stage', 'committee', 'questionset', 'question'])
            ->where('id', $student_question_selection_id)->first();

        if (!$studentQuestionSelection) {
            abort(404);
        }

        $evaluationElements = EvaluationElement::where('level', $studentQuestionSelection->level)->get();
        //  return$studentQuestionSelection;

        return view('student.start_evaluation', compact('studentQuestionSelection', 'evaluationElements'));
    }

    public function saveEvaluation(Request $request)
    {
        $request->validate([
            'student_question_selection_id' => 'required|exists:student_question_selections,id',
            'elements' => 'required|array',
            'elements.*' => 'required|min:0',
        ]);

        $selectionId = $request->student_question_selection_id;

        $judgeId = auth()->id();

        // 1. Remove old data (only from this judge)
        JudgeEvaluation::where('student_question_selection_id', $selectionId)
            ->where('judge_id', $judgeId)
            ->delete();


        // 2. Insert new evaluations
        foreach ($request->elements as $elementId => $score) {
            JudgeEvaluation::create([
                'student_question_selection_id' => $selectionId,
                'evaluation_element_id' => $elementId,
                'judge_id' => $judgeId,
                'achieved_point' => $score,
            ]);
        }

        $judgeEvaluationTotals = JudgeEvaluation::where('student_question_selection_id', $selectionId)
            ->where('judge_id', $judgeId)
            ->sum('achieved_point');

        QuestionJudgeEvaluation::create([
            'student_question_selection_id' => $selectionId,
            'judge_id' => $judgeId,
            'total_question' => $judgeEvaluationTotals,
        ]);

        // 3. Update total for this student (sum from all judges)
        $totalElementEvaluations = QuestionJudgeEvaluation::where([
            'student_question_selection_id' => $selectionId,
            'total_question' => $judgeEvaluationTotals,
        ])->sum('total_question');

        $judgeCount = JudgeEvaluation::where('student_question_selection_id', $selectionId)
            ->groupBy('judge_id')
            ->pluck('judge_id')
            ->count();

        $studentQuestionSelection = StudentQuestionSelection::find($selectionId);

        $studentQuestionSelection->update(['total_element_evaluation' => $totalElementEvaluations]);

        $totalQuestionEvaluations = StudentQuestionSelection::where('questionset_id', $studentQuestionSelection->questionset_id)
            ->sum('total_element_evaluation');

        $competition = Competition::where('questionset_id', $studentQuestionSelection->questionset_id)->first();

        if (!$competition) {
            abort(404);
        }

        $competition->update(['grand_total' => $totalQuestionEvaluations, 'judge_count' => $judgeCount]);


        return redirect()
            ->route('student.choose_questionset', $studentQuestionSelection->competition_id)
            ->with('success', 'تم حفظ التقييم بنجاح');
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
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|size:8|unique:students,phone',
            'national_id'       => 'nullable|string|max:11|unique:students,national_id',
            'nationality'       => 'required|string|max:255',
            'dob'               => 'required|date',
            'state'             => 'required|string|max:255',
            'wilaya'            => 'required|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'level'            => 'nullable|string|max:255',
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
            'nationality'       => 'required|string|max:255',
            'dob'               => 'required|date',
            'state'             => 'required|string|max:255',
            'wilaya'            => 'required|string|max:255',
            'qarya'             => 'nullable|string|max:255',
            'level'             => 'nullable|string|max:255',
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
