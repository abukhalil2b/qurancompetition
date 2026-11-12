<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Stage;

class AdminStudentController extends Controller
{
    public function femaleAttendanceIndex()
    {
        $user = auth()->user();

        abort_unless(($user->user_type == 'organizer'), 403, 'هذه الصفحة مخصصة لمنظم المسابقة.');

        // 1. Active stage
        $stage = Stage::whereActive(1)->latest('id')->first();

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
            ->whereRelation('committee', 'center_id', $center->id)
            ->where('committee_id', $committee->id)
            ->orderByRaw("CASE WHEN student_status = 'present' THEN 0 ELSE 1 END ASC")
            ->orderByRaw('CASE WHEN present_at IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('present_at', 'ASC')
            ->orderBy('student_status', 'ASC')
            ->get();

        $judges = CommitteeUser::with('user')
            ->where('committee_id', $committee->id)
            ->whereHas('user', function ($query) {
                $query->where('users.user_type', 'judge');
            })
            ->get();

        $stats = [
            'total' => $competitions->count(),
            'withdrawn' => $competitions->where('student_status', 'withdraw')->count(),
            'active' => $competitions->where('student_status', '!=', 'withdraw')->count(),
            'registered' => $competitions->where('student_status', 'registration')->count(),
            'in_hall' => $competitions->where('student_status', 'present')->count(), // بانتظار الدخول للجنة
            'with_committee' => $competitions->where('student_status', 'with_committee')->count(), // مع اللجنة حالياً
            'finished' => $competitions->whereIn('student_status', ['waiting_finalization', 'finish_competition'])->count(),
        ];

        return view('admin.student.female_attendance_index', compact(
            'stats',
            'stage',
            'center',
            'committee',
            'competitions',
            'judges'
        ));
    }
}
