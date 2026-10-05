<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionset;
use App\Models\QuranAya;
use App\Models\QuranSurat;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
   

    public function create(Questionset $questionset)
    {
        $surats = QuranSurat::query()
            ->orderBy('number')
            ->get(['id', 'number', 'title']);

        $juzs = range(1, 30);

        return view('question.create', compact('questionset', 'surats', 'juzs'));
    }

    /**
     * Get ayas for a selected surat.
     */
    public function ayas(QuranSurat $quranSurat)
    {
        $ayas = $quranSurat->ayas()
            ->orderBy('number')
            ->get([
                'id',
                'number',
                'content',
                'page_number',
            ]);

        return response()->json($ayas);
    }

    /**
     * Store a new question.
     */
    public function store(Request $request, Questionset $questionset)
    {
        $validated = $request->validate([
            'riwaya' => [
                'required',
                'string',
                'max:50',
            ],

            'quran_surat_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'juz' => [
                'required',
                'integer',
                'between:1,30',
            ],

            'aya_from' => [
                'required',
                'integer',
                'min:1',
            ],

            'aya_to' => [
                'required',
                'integer',
                'gte:aya_from',
            ],
        ]);

        /*
         * Make sure the starting ayah exists
         * in the selected surat.
         */
        $fromExists = QuranAya::query()
            ->where('quran_surat_id', $validated['quran_surat_id'])
            ->where('number', $validated['aya_from'])
            ->exists();

        /*
         * Make sure the ending ayah exists
         * in the selected surat.
         */
        $toExists = QuranAya::query()
            ->where('quran_surat_id', $validated['quran_surat_id'])
            ->where('number', $validated['aya_to'])
            ->exists();

        if (! $fromExists || ! $toExists) {
            return back()
                ->withErrors([
                    'aya_from' => 'نطاق الآيات المحدد غير صحيح.',
                ])
                ->withInput();
        }

        /*
         * Create the question through the
         * Questionset relationship.
         */
        $questionset->questions()->create([
            'quran_surat_id' => $validated['quran_surat_id'],
            'riwaya' => $validated['riwaya'],
            'juz' => $validated['juz'],
            'aya_from' => $validated['aya_from'],
            'aya_to' => $validated['aya_to'],
        ]);

        return redirect()
            ->route('questionset.show', $questionset)
            ->with('success', 'تمت إضافة السؤال بنجاح.');
    }

    /**
     * Show a question.
     */
    public function show(Question $question)
    {
        $question->load([
            'questionset',
            'surat',
        ]);

        /*
         * Get all ayas belonging to the
         * question's range.
         */
        $ayas = QuranAya::query()
            ->where('quran_surat_id', $question->quran_surat_id)
            ->whereBetween('number', [
                $question->aya_from,
                $question->aya_to,
            ])
            ->orderBy('number')
            ->get([
                'id',
                'number',
                'page_number',
                'content',
            ]);

        return view('question.show', compact(
            'question',
            'ayas'
        ));
    }

    /**
     * Show edit question form.
     */
    public function edit(Question $question)
    {
        $question->load('questionset');

        $surats = QuranSurat::query()
            ->orderBy('number')
            ->get([
                'id',
                'number',
                'title',
            ]);

        $juzs = range(1, 30);

        return view('question.edit', compact(
            'question',
            'surats',
            'juzs'
        ));
    }

    /**
     * Update an existing question.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'riwaya' => [
                'required',
                'string',
                'max:50',
            ],

            'quran_surat_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'juz' => [
                'required',
                'integer',
                'between:1,30',
            ],

            'aya_from' => [
                'required',
                'integer',
                'min:1',
            ],

            'aya_to' => [
                'required',
                'integer',
                'gte:aya_from',
            ],
        ]);

        /*
         * Make sure the starting ayah exists
         * in the selected surat.
         */
        $fromExists = QuranAya::query()
            ->where('quran_surat_id', $validated['quran_surat_id'])
            ->where('number', $validated['aya_from'])
            ->exists();

        /*
         * Make sure the ending ayah exists
         * in the selected surat.
         */
        $toExists = QuranAya::query()
            ->where('quran_surat_id', $validated['quran_surat_id'])
            ->where('number', $validated['aya_to'])
            ->exists();

        if (! $fromExists || ! $toExists) {
            return back()
                ->withErrors([
                    'aya_from' => 'نطاق الآيات المحدد غير صحيح.',
                ])
                ->withInput();
        }

        /*
         * Update the question.
         */
        $question->update([
            'quran_surat_id' => $validated['quran_surat_id'],
            'riwaya' => $validated['riwaya'],
            'juz' => $validated['juz'],
            'aya_from' => $validated['aya_from'],
            'aya_to' => $validated['aya_to'],
        ]);

        return redirect()
            ->route('questionset.show', $question->questionset_id)
            ->with('success', 'تم تحديث السؤال بنجاح.');
    }
}
