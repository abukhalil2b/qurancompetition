<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\Committee;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{

    public function present(Request $request)
    {
        $request->validate([
            'student_id'     => 'required|exists:students,id',
            'committee_id'   => 'required|exists:committees,id',
        ]);

        $student  = Student::findOrFail($request->student_id);
        $stage    = Stage::where('active', 1)->first();

        if (!$stage) {
            return back()->with('error', 'لا توجد مرحلة نشطة حالياً');
        }

        // Check if already registered
        $already = Competition::where('student_id', $student->id)
            ->where('stage_id', $stage->id)
            ->first();

        if ($already) {
            return back()->with('error', 'المتسابق مسجل مسبقاً في هذه المرحلة');
        }

        // Detect center from committee automatically
        $committee = Committee::findOrFail($request->committee_id);

        // Create competition record
        Competition::create([
            'center_id'      => $committee->center_id,
            'stage_id'       => $stage->id,
            'committee_id'   => $committee->id,
            'student_id'     => $student->id,
            'student_status' => 'present',
        ]);

        return redirect()
            ->route('student.index')
            ->with('success', 'تم تسجيل حضور المتسابق بنجاح');
    }
}
