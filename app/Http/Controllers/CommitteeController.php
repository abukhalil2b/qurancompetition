<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
public function index()
{
    $stage = Stage::whereActive(true)->latest('id')->first();

    if (! $stage) {
        abort(403, 'لم يتم تحديد مرحلة التصفيات');
    }

    $committees = Committee::with(['center', 'users'])
        ->where('stage_id', $stage->id)
        ->get()
        ->groupBy('center_id');

    // Count competitions (students) per center for the badge
    $centerStudentCounts = Competition::query()
        ->where('stage_id', $stage->id)
        ->selectRaw('center_id, COUNT(*) as total')
        ->groupBy('center_id')
        ->pluck('total', 'center_id');

    $centers = Center::orderBy('title')->get();

    return view('committee.index', compact(
        'committees',
        'centers',
        'stage',
        'centerStudentCounts'
    ));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|string|max:7',
            'title' => 'required|string|max:255',
            'center_id' => 'required|exists:centers,id',
        ]);

        Committee::create([
            'title' => $validated['title'],
            'center_id' => $validated['center_id'],
            'gender' => $validated['gender'],
        ]);

        return back()->with('success', 'تم إضافة اللجنة بنجاح');
    }

    public function update(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'gender' => 'required|in:males,females',
            'center_id' => 'required|exists:centers,id',
            'active' => 'boolean',
        ]);

        $committee->update($validated);

        return back()->with('success', 'تم تحديث بيانات اللجنة بنجاح');
    }

    public function setLeader(Request $request, Committee $committee)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 1. Reset all members of this committee to NOT be leaders
        $committee->users()->updateExistingPivot($committee->users->pluck('id'), [
            'is_judge_leader' => false,
        ]);

        // 2. Set the selected user as leader
        $committee->judges()->updateExistingPivot($request->user_id, [
            'is_judge_leader' => true,
        ]);

        return back()->with('success', 'تم تعيين رئيس اللجنة بنجاح');
    }

    public function removeUser(Committee $committee, User $user)
    {
        // Detach the user from the committee's judges relationship
        $committee->users()->detach($user->id);

        return back()->with('success', 'تم حذف المحكم من اللجنة بنجاح');
    }
}
