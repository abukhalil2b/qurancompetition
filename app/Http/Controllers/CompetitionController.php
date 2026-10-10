<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompetitionController extends Controller
{
    public function edit(Competition $competition)
    {
        $competition->load(['student', 'center', 'stage', 'committee', 'questionset']);

        // Full list of committees — the user picks a committee, and the
        // center is derived from the selected committee.
        $committees = Committee::with('center')
            ->orderBy('center_id')
            ->orderBy('title')
            ->get();

        // Group them for a nicer <select> (optgroup by center title)
        $committeesByCenter = $committees->groupBy(fn ($c) => $c->center->title ?? 'بدون مركز');

        return view('competition.student.edit', compact('competition', 'committees', 'committeesByCenter'));
    }

    public function update(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'level'=>'required',
            'committee_id' => ['nullable', Rule::exists('committees', 'id')],
            'student_status' => [
                'required',
                Rule::in([
                    'registration',
                    'present',
                    'withdraw',
                ]),
            ],
        ]);

        // Derive center_id, stage_id from the selected committee
        if (! empty($validated['committee_id'])) {
            $committee = Committee::find($validated['committee_id']);
            $validated['center_id'] = $committee->center_id;
            $validated['stage_id'] = $committee->stage_id;
        }

        // Business rules ------------------------------------------------

        // with_committee requires a committee
        if ($validated['student_status'] === 'with_committee' && empty($validated['committee_id'])) {
            return back()->withInput()
                ->withErrors(['committee_id' => 'يجب اختيار لجنة عند تعيين حالة "مع اللجنة".']);
        }

        // Locked fields are explicitly NOT in $validated, so even if the
        // user crafts a POST with those fields, they are ignored.

        $competition->update($validated);

        return redirect()
            ->route('competition.student.index', $competition->center_id)
            ->with('success', 'تم تحديث بيانات المتسابق بنجاح.');
    }

    public function index(Center $center)
    {
        $stage = Stage::whereActive(true)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لم يتم تحديد مرحلة التصفيات');
        }

        // Students belonging to this center
        // who are not yet registered in this stage.
        $unregisteredStudents = Student::query()
            ->whereDoesntHave('competitions', function ($query) use ($stage) {
                $query->where('stage_id', $stage->id);
            })
            ->orderBy('id')
            ->get(['id', 'name', 'level', 'phone']);

        $competitions = Competition::with([
            'student:id,name,phone',
            'committee:id,title',
        ])
            ->where('center_id', $center->id)
            ->where('stage_id', $stage->id)
            ->orderBy('id')
            ->get([
                'id',
                'student_id',
                'committee_id',
                'level',
                'position',
                'student_status',
            ]);

        $committees = Committee::where('center_id', $center->id)->get();

        return view('competition.student.index', compact(
            'committees',
            'center',
            'stage',
            'unregisteredStudents',
            'competitions'
        ));
    }

    public function print(Center $center, Committee $committee)
    {
        $stage = Stage::whereActive(true)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لم يتم تحديد مرحلة التصفيات');
        }

        $competitions = Competition::with(['student:id,name,phone', 'committee:id,title'])
            ->where('center_id', $center->id)
            ->where('stage_id', $stage->id)
            ->where('committee_id', $committee->id)
            ->orderBy('id')
            ->get(['id', 'student_id', 'committee_id', 'level', 'position', 'student_status']);


        return view('competition.student.print', compact(
            'center',
            'stage',
            'competitions'
        ));
    }

    public function store(Request $request, Center $center)
    {
        $stage = Stage::whereActive(true)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لم يتم تحديد مرحلة التصفيات');
        }

        $request->validate([
            'committee_id' => 'required',
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'exists:students,id'],
        ]);

        $students = Student::query()
            ->whereIn('id', $request->student_ids)
            ->get(['id', 'level']);

        $now = now();

        $rows = $students->map(fn ($student) => [
            'committee_id' => $request->committee_id,
            'center_id' => $center->id,
            'stage_id' => $stage->id,
            'student_id' => $student->id,
            'level' => $student->level,
            'student_status' => 'registration',
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        Competition::insertOrIgnore($rows);

        return back()->with('success', 'تمت إضافة المتسابقين بنجاح');
    }

    public function present(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $competition = Competition::find($request->competition_id);

        if (! $competition) {
            abort(403, 'المتسابق غير مسجل في سجل المتسابقين');
        }

        if ($competition->student_status !== 'registration') {
            return back()->with(
                'error',
                'لا يمكن تسجيل حضور المتسابق في حالته الحالية'
            );
        }

        $competition->update(['student_status' => 'present', 'present_at' => now()]);

        return redirect()
            ->route('student.attendance_index')
            ->with('success', 'تم تسجيل حضور المتسابق بنجاح');
    }
}
