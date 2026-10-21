<?php

namespace App\Http\Controllers;

use App\Models\CommitteeUser;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function testDashboard()
    {

        $users = User::all();

        return view('test_dashboard', compact('users'));
    }

    public function testLoginToUserAccount(Request $request, User $user)
    {
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function welcome()
    {

        // return view('report');
        return view('welcome');
    }

    public function dashboard()
    {

        $activeStage = Stage::where('active', 1)->first();

        if (! $activeStage) {
            abort(404);
        }

        $loggedUser = auth()->user();

        if ($loggedUser->user_type == 'organizer') {
            return redirect()->route('student.attendance_index');
        }

        if ($loggedUser->user_type == 'judge') {

            // dashboard for judge
            $committeeUser = CommitteeUser::where('user_id', $loggedUser->id)->first();

            if (! $committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            $readyStudent = Competition::where('committee_id', $committeeUser->committee_id)->first();

            return view('judge_dashboard', compact('readyStudent', 'activeStage'));
        }

        if ($loggedUser->user_type == 'commite') {

            $committeeUser = CommitteeUser::where('stage_id', $activeStage->id)->where('user_id', $loggedUser->id)->first();

            if (! $committeeUser) {
                abort(403, 'يجب أن يرتبط المستخم بلجنة معينة. التواصل مع الدعم الفني');
            }

            return view('commite_dashboard', compact('activeStage'));
        }

        return view('dashboard', compact('activeStage'));
    }
}
