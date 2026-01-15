<x-app-layout>
    <div class="max-w-6xl mx-auto py-8" dir="rtl">

        {{-- Top Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">تقرير النتيجة: {{ $student->name }}</h2>
            <div class="text-sm text-gray-500">المستوى: {{ $student->level }}</div>
        </div>

        {{-- 1. Memorization Questions Loop --}}
        @foreach ($questions as $qIndex => $selection)
            <div class="bg-white border rounded-xl shadow p-5 mb-6 break-inside-avoid">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-lg text-gray-800">
                        السؤال {{ $qIndex + 1 }}: <span
                            class="text-xs font-normal text-gray-500">{{ $selection->question->content }}</span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="border px-3 py-2 text-right w-1/4">العنصر</th>
                                <th class="border px-3 py-2 text-center w-24">الدرجة</th>
                                <th class="border px-3 py-2 text-right">الخصومات</th>
                                <th class="border px-3 py-2 text-center w-24">معدل الخصم</th>
                                <th class="border px-3 py-2 text-center w-24">الدرجة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $elementGroups = $selection->judgeEvaluations->groupBy('evaluation_element_id');
                            @endphp

                            @foreach ($elementGroups as $elementId => $evaluations)
                                @php
                                    $element = $evaluations->first()->element;
                                    $maxScore = $element->max_score;
                                    $avgDeduction = $evaluations->avg('reduct_point');
                                    $finalScore = $maxScore - $avgDeduction;
                                @endphp
                                <tr>
                                    <td class="border px-3 py-2 font-semibold">{{ $element->title }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $maxScore }}</td>
                                    <td class="border px-3 py-2 text-red-600 text-xs">
                                        @foreach ($evaluations as $ev)
                                            <span
                                                class="inline-block bg-red-50 px-1 rounded ml-1 border border-red-100">
                                                {{ $ev->judge->name ?? 'محكم' }}: -{{ $ev->reduct_point }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="border px-3 py-2 text-center font-bold text-red-600">
                                        -{{ number_format($avgDeduction, 2) }}
                                    </td>
                                    <td class="border px-3 py-2 text-center font-bold text-green-700 bg-green-50">
                                        {{ number_format($finalScore, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            @php
                                // 1. Calculate the raw deduction average
                                $totalDeduction = $elementGroups->map(fn($ev) => $ev->avg('reduct_point'))->sum();

                                // 2. Calculate the theoretical score
                                $rawScore = $elementGroups
                                    ->map(function ($ev) {
                                        return $ev->first()->element->max_score - $ev->avg('reduct_point');
                                    })
                                    ->sum();

                                // 3. APPLY THE RULE: If failed, score is 0
                                $questionTotalScore = $selection->is_passed ? $rawScore : 0;
                            @endphp

                            <tr class="bg-indigo-50 border-t-2 border-indigo-200">
                                <td colspan="3" class="border px-3 py-2 font-bold text-indigo-800 text-left pl-4">
                                    الإجمالي للسؤال:
                                </td>
                                <td class="border px-3 py-2 font-bold text-red-700 text-center">
                                    {{-- Show deductions even if failed, so they know why --}}
                                    -{{ number_format($totalDeduction, 2) }}
                                </td>
                                <td class="border px-3 py-2 font-bold text-indigo-800 text-center text-lg">

                                    {{-- This will now show 0.00 if failed --}}
                                    {{ number_format($questionTotalScore, 2) }}

                                    {{-- FIXED CONDITION: Use '!' (Not passed) --}}
                                    @if (!$selection->is_passed)
                                        <div
                                            class="text-[10px] font-bold text-red-600 mt-1 border border-red-200 bg-red-50 rounded px-1">
                                            السؤال ملغي
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- 2. Tafseer Section (If Applicable) --}}
        @if (isset($tafseerResult) && $student->level === 'حفظ وتفسير')
            <div class="bg-white border rounded-xl shadow p-5 mb-6 break-inside-avoid border-orange-200">
                <h3 class="font-bold text-lg text-orange-800 mb-4 border-b border-orange-100 pb-2">
                    تفاصيل درجات التفسير
                </h3>
                <div class="space-y-2">
            @php
                // Fetch all raw evaluations for this competition
                $allTafseerEvals = \App\Models\TafseerEvaluation::with('judge')
                    ->where('competition_id', $competition->id)
                    ->get()
                    ->groupBy('judge_id');
            @endphp

            @foreach($allTafseerEvals as $judgeId => $evals)
                <div class="flex justify-between text-xs bg-white border border-orange-100 p-2 rounded">
                    <span class="font-bold">{{ $evals->first()->judge->name }}</span>
                    <span>
                        المجموع: {{ $evals->sum('score') }}
                        {{-- Note: This assumes simple sum. If you average per question, logic differs --}}
                    </span>
                </div>
            @endforeach
        </div>
                <div class="flex justify-between items-center bg-orange-50 p-4 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">تم تقييم التفسير بنجاح</p>
                    </div>
                    <div class="text-2xl font-bold text-orange-700">
                        {{ number_format($tafseerResult->total_score, 2) }} / 40
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. Grand Total Section --}}
        <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-lg mb-8 text-center break-inside-avoid">
            <h2 class="text-xl opacity-80 mb-2">المجموع الكلي النهائي</h2>
            <div class="text-5xl font-extrabold tracking-wider">
                {{ number_format($scores['total'], 2) }}
                <span class="text-2xl font-normal opacity-50">/ {{ $scores['max'] }}</span>
            </div>
        </div>

        {{-- 4. Action Buttons (Hide on Print) --}}
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-10 print:hidden">

            {{-- Print Button --}}
            <button onclick="window.print()"
                class="px-6 py-3 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                طباعة التقرير
            </button>

            {{-- Finish Competition Form --}}
            @if ($competition->student_status !== 'finish_competition')
                @if ($isJudgeLeader)
                    <form action="{{ route('competition.finalize', $competition->id) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من اعتماد النتيجة النهائية؟ لا يمكن التراجع بعد ذلك.');">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            اعتماد النتيجة وإنهاء المسابقة
                        </button>
                    </form>
                @endif
            @else
                <div class="px-6 py-3 bg-green-100 text-green-800 rounded-lg border border-green-200 font-bold">
                    تم اعتماد النتيجة
                </div>
            @endif
        </div>

        {{-- 5. Navigation/Questions List (Hide on Print) --}}
        <div class="border-t pt-8 print:hidden">
            <h3 class="text-lg font-bold text-gray-700 mb-4">قائمة الأسئلة وحالة الإنجاز</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($questions as $selection)
                    <a href="{{ route('memorization.start', $selection->id) }}"
                        class="group p-4 border rounded-xl transition-all duration-200 flex items-center gap-4
                       {{ $selection->done
                           ? 'border-green-200 bg-green-50 hover:border-green-300'
                           : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-md' }}">

                        {{-- Number Badge --}}
                        <span
                            class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-white shrink-0
                            {{ $selection->done ? 'bg-green-500' : 'bg-gray-400 group-hover:bg-indigo-500' }}">
                            {{ $selection->position }}
                        </span>

                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm mb-1">
                                السؤال {{ $selection->position }}
                            </h4>

                            @if ($selection->done)
                                <div class="text-xs text-green-700 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    تم التقييم
                                </div>
                                {{-- Show unique judges who evaluated this question --}}
                                <div class="text-[10px] text-gray-500 mt-1">
                                    بواسطة:
                                    {{ $selection->judgeEvaluations->unique('judge_id')->pluck('judge.name')->join('، ') }}
                                </div>
                            @else
                                <div class="text-xs text-gray-500">
                                    بانتظار التقييم...
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

    {{-- CSS for Print formatting --}}
    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }

            body {
                background: white;
            }

            .shadow,
            .shadow-lg {
                box-shadow: none !important;
            }

            .bg-indigo-900 {
                color: black !important;
                background: none !important;
                border: 2px solid black;
            }

            .text-white {
                color: black !important;
            }
        }
    </style>
</x-app-layout>
