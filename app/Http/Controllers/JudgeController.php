<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class JudgeController extends Controller
{
    public function index()
    {
        $users = User::with([
            'committees.stage',
        ])->get();

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
            'user_type' => 'required|string|max:9',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'national_id' => 'nullable|string|max:10|unique:users,national_id',
        ]);

        User::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'national_id' => $validated['national_id'] ?? null,
            'user_type' => $validated['user_type'],
            'password' => bcrypt('123456'), // default
        ]);

        return redirect()->route('user.index')
            ->with('success', 'تم إضافة المحكم بنجاح');
    }

    public function assignCommittees(Request $request)
    {
        $request->validate([
            'judge_id' => 'required|exists:users,id',
            'committee_ids' => 'required|array',
            'committee_ids.*' => 'exists:committees,id',
        ]);

        $user = User::findOrFail($request->judge_id);
        $stage = Stage::where('active', 1)->firstOrFail();

        // Committee IDs that belong to the active stage
        $stageCommitteeIds = Committee::where('stage_id', $stage->id)
            ->pluck('id');

        // Detach only the committees belonging to this stage
        $user->committees()
            ->whereIn('committees.id', $stageCommitteeIds)
            ->detach();

        // Attach only committees that belong to the active stage
        $attachIds = collect($request->committee_ids)
            ->intersect($stageCommitteeIds)
            ->values()
            ->all();

        $user->committees()->attach($attachIds);

        return back()->with('success', 'تم ربط المحكّم باللجان بنجاح');
    }
}
