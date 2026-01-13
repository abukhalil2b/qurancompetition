<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['center', 'judges'])->latest('id')->get();

        $centers = Center::all();

        return view('committee.index', compact('committees', 'centers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|string|max:7',
            'title' => 'required|string|max:255',
            'center_id' => 'required|exists:centers,id',
        ]);

        Committee::create([
            'title'     => $validated['title'],
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
        ]);

        $committee->update($validated);

        return back()->with('success', 'تم تحديث بيانات اللجنة بنجاح');
    }


    public function setLeader(Request $request, Committee $committee)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // 1. Reset all members of this committee to NOT be leaders
        $committee->judges()->updateExistingPivot($committee->judges->pluck('id'), [
            'is_judge_leader' => false
        ]);

        // 2. Set the selected user as leader
        $committee->judges()->updateExistingPivot($request->user_id, [
            'is_judge_leader' => true
        ]);

        return back()->with('success', 'تم تعيين رئيس اللجنة بنجاح');
    }

    public function removeJudge(Committee $committee, User $user)
{
    // Detach the user from the committee's judges relationship
    $committee->judges()->detach($user->id);

    return back()->with('success', 'تم حذف المحكم من اللجنة بنجاح');
}

}
