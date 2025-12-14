<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::all();
        return view('stage.index', compact('stages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $data['active'] = $request->has('active') ? true : false;

        Stage::create($data);

        return redirect()->route('stage.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function toggleActive(Stage $stage)
    {
        // 1. Disable all stages
        Stage::query()->update(['active' => 0]);

        // 2. Activate the requested stage
        $stage->update(['active' => 1]);

        return redirect()->route('stage.index')
            ->with('success', 'تم تحديث حالة المرحلة بنجاح');
    }
}
