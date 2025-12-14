<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Committee;
use App\Models\Stage;
use Illuminate\Http\Request;

class JudgeController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    public function show(User $user)
    {
        // load committees linked to the user
        $user->load('committees');

        // all committees from all centers
        $committees = Committee::with('center')->get();

        return view('user.show', compact('user', 'committees'));
    }

    public function create()
    {
        $userTypes = ['judge', 'admin', 'organizer', 'student'];

        return view('user.create', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_type'        => 'required|string|max:9',
            'name'        => 'required|string|max:255',
            'gender'      => 'required|in:male,female',
            'national_id' => 'nullable|string|max:10|unique:users,national_id',
        ]);

        User::create([
            'name'        => $validated['name'],
            'gender'      => $validated['gender'],
            'national_id' => $validated['national_id'] ?? null,
            'user_type'   => $validated['user_type'],
            'password'    => bcrypt('123456'), // default
        ]);

        return redirect()->route('user.index')
            ->with('success', 'تم إضافة المحكم بنجاح');
    }


    public function assignCommittees(Request $request)
    {
        $request->validate([
            'judge_id'       => 'required|exists:users,id',
            'committee_ids'  => 'required|array',
            'committee_ids.*' => 'exists:committees,id',
        ]);

        $user = User::findOrFail($request->judge_id);

        $stage = Stage::where('active', 1)->firstOrFail();

        // Remove previous committee assignments for this stage only
        $user->committees()
            ->wherePivot('stage_id', $stage->id)
            ->detach();

        // Reassign committees for this stage
        foreach ($request->committee_ids as $committee_id) {
            $user->committees()->attach($committee_id, [
                'stage_id' => $stage->id,
            ]);
        }

        return back()->with('success', 'تم ربط المحكّم باللجان بنجاح');
    }
}
