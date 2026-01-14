<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{

    public function present(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'committee_id' => 'required|exists:committees,id',
        ]);

        $student   = Student::findOrFail($request->student_id);
        $committee = Committee::findOrFail($request->committee_id);

        $stage = Stage::where('active', 1)->orderByDesc('id')->first();
        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        $already = Competition::where('student_id', $student->id)
            ->where('stage_id', $stage->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'المتسابق مسجل مسبقاً في هذه المرحلة');
        }

        $levelMap = [
            'حفظ'        => 'memorize',
            'حفظ وتفسير' => 'memorize_with_tafseer',
        ];

        $level = $levelMap[$student->level] ?? null;
        if (!$level) {
            return back()->with('error', 'مستوى الطالب غير معرّف');
        }

        DB::transaction(function () use ($committee, $stage, $student, $level) {
            $maxPosition = Competition::where('committee_id', $committee->id)
                ->where('stage_id', $stage->id)
                ->lockForUpdate()//lock rows until I’m done.
                ->max('position');

            Competition::create([
                'center_id'      => $committee->center_id,
                'stage_id'       => $stage->id,
                'committee_id'   => $committee->id,
                'student_id'     => $student->id,
                'student_status' => 'present',
                'level'          => $level,
                'position'       => ($maxPosition ?? 0) + 1,
            ]);
        });

        return redirect()
            ->route('student.index')
            ->with('success', 'تم تسجيل حضور المتسابق بنجاح');
    }
}
