<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionset;
use App\Models\QuranAya;
use App\Models\QuranSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'riwaya' => ['required', 'string', 'max:50'],

            'quran_surat_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'quran_surat_to_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'juz' => ['required', 'integer', 'between:1,30'],

            'aya_from' => ['required', 'integer', 'min:1'],
            'aya_to' => ['required', 'integer', 'min:1'],
        ]);

        $startSurat = QuranSurat::findOrFail(
            $validated['quran_surat_id']
        );

        $endSurat = QuranSurat::findOrFail(
            $validated['quran_surat_to_id']
        );

        // Ending Surah must not precede starting Surah.
        if ($endSurat->number < $startSurat->number) {
            return back()
                ->withErrors([
                    'quran_surat_to_id' => 'يجب أن تكون السورة الأخيرة بعد السورة الأولى أو مساوية لها.',
                ])
                ->withInput();
        }

        // When both Surahs are the same, the ending Ayah
        // must not precede the starting Ayah.
        if (
            $startSurat->id === $endSurat->id &&
            $validated['aya_to'] < $validated['aya_from']
        ) {
            return back()
                ->withErrors([
                    'aya_to' => 'يجب أن تكون الآية الأخيرة أكبر من أو مساوية للآية الأولى.',
                ])
                ->withInput();
        }

        // Validate starting Ayah.
        $fromExists = QuranAya::query()
            ->where('quran_surat_id', $startSurat->id)
            ->where('number', $validated['aya_from'])
            ->exists();

        // Validate ending Ayah.
        $toExists = QuranAya::query()
            ->where('quran_surat_id', $endSurat->id)
            ->where('number', $validated['aya_to'])
            ->exists();

        if (! $fromExists || ! $toExists) {
            return back()
                ->withErrors([
                    'aya_from' => 'نطاق الآيات المحدد غير صحيح.',
                ])
                ->withInput();
        }

        $questionset->questions()->create([
            'riwaya' => $validated['riwaya'],
            'quran_surat_id' => $startSurat->id,
            'quran_surat_to_id' => $endSurat->id,
            'juz' => $validated['juz'],
            'aya_from' => $validated['aya_from'],
            'aya_to' => $validated['aya_to'],
        ]);

        return redirect()
            ->route('questionset.show', $questionset)
            ->with('success', 'تمت إضافة السؤال بنجاح.');
    }

    public function replicate(Request $request, Questionset $questionset)
    {
        $newQuestionset = DB::transaction(function () use ($questionset) {

            // نسخ الباقة نفسها مع تعديل العنوان
            $copy = $questionset->replicate();
            $copy->title = $questionset->title;
            $copy->selected = 0;
            $copy->save();

            // نسخ الأسئلة وربطها بالباقة الجديدة
            foreach ($questionset->questions as $question) {
                $copy->questions()->save($question->replicate());
            }

            return $copy;
        });

        return redirect()
            ->route('questionset.show', $newQuestionset)
            ->with('success', 'تم تكرار الباقة بنجاح.');
    }

    public function show(Question $question)
    {
        $question->load([
            'questionset',
            'surat',
            'suratTo',
        ]);

        $endSurat = $question->suratTo ?? $question->surat;

        $ayas = QuranAya::query()
            ->join('quran_surats', 'quran_surats.id', '=', 'quran_ayas.quran_surat_id')

            ->whereBetween('quran_surats.number', [
                $question->surat->number,
                $endSurat->number,
            ])

            // Starting Surah: begin at aya_from.
            ->where(function ($query) use ($question) {
                $query->where('quran_surats.number', '>', $question->surat->number)
                    ->orWhere(function ($sub) use ($question) {
                        $sub->where('quran_surats.number', $question->surat->number)
                            ->where('quran_ayas.number', '>=', $question->aya_from);
                    });
            })

            // Ending Surah: stop at aya_to.
            ->where(function ($query) use ($question, $endSurat) {
                $query->where('quran_surats.number', '<', $endSurat->number)
                    ->orWhere(function ($sub) use ($question, $endSurat) {
                        $sub->where('quran_surats.number', $endSurat->number)
                            ->where('quran_ayas.number', '<=', $question->aya_to);
                    });
            })

            ->orderBy('quran_surats.number')
            ->orderBy('quran_ayas.number')

            ->get([
                'quran_ayas.id',
                'quran_ayas.quran_surat_id',
                'quran_ayas.number',
                'quran_ayas.page_number',
                'quran_ayas.content',
                'quran_surats.title as surat_title',
                'quran_surats.number as surat_number',
            ])

            ->groupBy('quran_surat_id');

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
        $question->load([
            'questionset',
            'surat',
            'suratTo',
        ]);

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
            'riwaya' => ['required', 'string', 'max:50'],

            'quran_surat_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'quran_surat_to_id' => [
                'required',
                'integer',
                'exists:quran_surats,id',
            ],

            'juz' => ['required', 'integer', 'between:1,30'],

            'aya_from' => ['required', 'integer', 'min:1'],
            'aya_to' => ['required', 'integer', 'min:1'],
        ]);

        $startSurat = QuranSurat::findOrFail(
            $validated['quran_surat_id']
        );

        $endSurat = QuranSurat::findOrFail(
            $validated['quran_surat_to_id']
        );

        // Ensure the ending Surah is not before the starting Surah.
        if ($endSurat->number < $startSurat->number) {
            return back()
                ->withErrors([
                    'quran_surat_to_id' => 'يجب أن تكون السورة الأخيرة بعد السورة الأولى أو مساوية لها.',
                ])
                ->withInput();
        }

        // If both Surahs are identical, validate the Ayah order.
        if (
            $startSurat->id === $endSurat->id &&
            $validated['aya_to'] < $validated['aya_from']
        ) {
            return back()
                ->withErrors([
                    'aya_to' => 'يجب أن تكون الآية الأخيرة أكبر من أو مساوية للآية الأولى.',
                ])
                ->withInput();
        }

        // Validate starting Ayah.
        $fromExists = QuranAya::query()
            ->where('quran_surat_id', $startSurat->id)
            ->where('number', $validated['aya_from'])
            ->exists();

        // Validate ending Ayah.
        $toExists = QuranAya::query()
            ->where('quran_surat_id', $endSurat->id)
            ->where('number', $validated['aya_to'])
            ->exists();

        if (! $fromExists || ! $toExists) {
            return back()
                ->withErrors([
                    'aya_from' => 'نطاق الآيات المحدد غير صحيح.',
                ])
                ->withInput();
        }

        $question->update([
            'riwaya' => $validated['riwaya'],
            'quran_surat_id' => $startSurat->id,
            'quran_surat_to_id' => $endSurat->id,
            'juz' => $validated['juz'],
            'aya_from' => $validated['aya_from'],
            'aya_to' => $validated['aya_to'],
        ]);

        return redirect()
            ->route('questionset.show', $question->questionset_id)
            ->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function destroy(Question $question)
    {
        $questionset = $question->questionset;

        if (! $questionset) {
            abort(404, 'الباقة المرتبطة بالسؤال غير موجودة');
        }

        if ($questionset->selected) {
            abort(403, 'لا يمكن حذف سؤال من باقة تم اختيارها');
        }

        $question->delete();

        return back()->with('success', 'تم حذف السؤال بنجاح');
    }
}
