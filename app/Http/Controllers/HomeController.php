<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\JudgeEvaluation;

use App\Models\Stage;
use App\Models\StudentQuestionSelection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    public function welcome()
    {
        // return view('report');
        return view('welcome');
    }

    public function dashboard()
    {

        $activeStage = Stage::where('active', 1)->first();

        if (!$activeStage) {
            abort(404);
        }

        $loggedUser = auth()->user();

        if ($loggedUser->user_type == 'judge') {

            // dashboard for judge
            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (!$committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            $readyStudent = Competition::where('committee_id', $committeeUser->committee_id)->first();

            return view('judge_dashboard', compact('readyStudent', 'activeStage'));
        }

        if ($loggedUser->user_type == 'commite') {

            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (!$committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }


            return view('commite_dashboard', compact('activeStage'));
        }


        return view('dashboard', compact('activeStage'));
    }




    public function finalResult()
    {
        // Fetch competitions with related student and center data
        $competitions = Competition::with(['student', 'center', 'stage'])
            ->where('student_status', 'finish_competition')
            ->get()
            ->map(function ($competition) {
                // Count how many questions were assigned to this student
                $questionCount = \App\Models\StudentQuestionSelection::where('competition_id', $competition->id)->count();

                // Calculate percentage: Grand Total / (Questions * Judges)
                $divisor = $questionCount * $competition->judge_count;
                $competition->percentage = $divisor > 0 ? ($competition->grand_total / $divisor) : 0;

                return $competition;
            })
            ->sortByDesc('percentage'); // Rank them from highest to lowest

        return view('final_result', compact('competitions'));
    }

    public function finishStudent($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->update(['student_status' => 'finish_competition']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إنهاء مسابقة الطالب بنجاح');
    }

    public function unFinishStudent($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->update(['student_status' => 'with_committee']);

        return redirect()->route('student.present_index')
            ->with('success', 'تم إعادة فتح مسابقة الطالب بنجاح');
    }
}
