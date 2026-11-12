<?php

namespace App\Http\Controllers;

use App\Models\Questionset;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionsetController extends Controller
{
    public function index($id = 1)
    {

        $stage = Stage::whereActive(1)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لابد من تفعيل المرحلة في النظام');
        }

        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = isset($levels[$id]) ? $id : 1;

        $level = $levels[$levelId];

        $questionsets = Questionset::withCount('questions')
            ->where('level', $levelId)
            ->where('stage_id', $stage->id)
            ->get();

        $totalCount = $questionsets->count();

        $notSelectedCount = $questionsets
            ->where('selected', false)
            ->count();

        return view('questionset.index', compact(
            'questionsets',
            'totalCount',
            'notSelectedCount',
            'level',
            'stage',
            'levelId'
        ));
    }

    public function create($level)
    {
        $stage = Stage::whereActive(1)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لابد من تفعيل المرحلة في النظام');
        }

        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = isset($levels[$level]) ? $level : 1;

        $level = $levels[$levelId];

        return view('questionset.create', compact(
            'level',
            'levelId',
            'stage'
        ));
    }

    public function store(Request $request)
    {
        $stage = Stage::whereActive(1)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لابد من تفعيل المرحلة في النظام');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:1,2',
        ]);

        Questionset::create([
            'title' => $request->title,
            'level' => $request->level,
            'stage_id' => $stage->id,
        ]);

        return redirect()
            ->route('questionset.index', $request->level)
            ->with('success', 'تم إنشاء باقة الأسئلة بنجاح');
    }

    public function show(Questionset $questionset)
    {
        $questions = $questionset->questions()
            ->with(['surat', 'suratTo'])
            ->join('quran_surats', 'questions.quran_surat_id', '=', 'quran_surats.id')
            ->orderBy('quran_surats.number')
            ->orderBy('questions.aya_from')
            ->select('questions.*')
            ->get();

        return view('questionset.show', compact('questionset', 'questions'));
    }

    public function edit(Questionset $questionset)
    {
        $levels = [
            1 => 'المستوى الأول',
            2 => 'المستوى الثاني',
        ];

        $levelId = $questionset->level;

        $level = $levels[$levelId] ?? $levels[1];

        return view('questionset.edit', compact(
            'questionset',
            'level',
            'levelId'
        ));
    }

    public function update(Request $request, Questionset $questionset)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|in:1,2',
        ]);

        $questionset->update([
            'title' => $request->title,
            'level' => $request->level,
        ]);

        return redirect()
            ->route('questionset.index', $request->level)
            ->with('success', 'تم تحديث باقة الأسئلة بنجاح');
    }

    public function print()
    {
        $stage = Stage::whereActive(1)->latest('id')->first();

        if (! $stage) {
            abort(403, 'لابد من تفعيل المرحلة في النظام');
        }

        $questionsets = Questionset::with('questions')
            ->where('stage_id', $stage->id)
            ->has('questions')
            ->get();

        return view('questionset.print', compact('questionsets'));
    }

    public function destroy(Questionset $questionset)
    {
        
        if($questionset->selected){
            abort(403,'لايمكن حذف باقة قد تم اختيارها');
        }

        DB::transaction(function () use ($questionset) {
            $questionset->questions()->delete();
            $questionset->delete();
        });

        return back()->with('success', 'تم حذف باقة الأسئلة');
    }
}
