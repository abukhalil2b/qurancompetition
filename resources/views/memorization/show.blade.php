<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        {{-- Header Card --}}
        <div class="bg-white shadow-sm border rounded-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">السؤال رقم:
                        {{ $studentQuestionSelection->position }}</h1>
                    <div class="mt-2 text-gray-600 leading-relaxed text-[10px]">
                        {{ $studentQuestionSelection->question->content }}
                    </div>
                </div>

                {{-- Fail/Pass Badge --}}
                @if ($studentQuestionSelection->is_passed == 0)
                    <div
                        class="flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-lg border border-red-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-bold">السؤال ملغي </span>
                    </div>
                @endif
            </div>
        </div>
        @php
            $evaluationsByJudge = $studentQuestionSelection->judgeEvaluations->groupBy('judge_id');
            $notesByJudge = $studentQuestionSelection->judgeNotes->keyBy('judge_id');
            $judgeTotals = [];
        @endphp

                {{-- Final Average Card --}}
        @if (count($judgeTotals) > 0)
            @php
                $questionAverage = collect($judgeTotals)->avg();
                // Adjust average display if the question is failed
                $displayAverage = $studentQuestionSelection->is_passed == 0 ? 0 : $questionAverage;
            @endphp

            <div class="relative overflow-hidden bg-indigo-600 text-white rounded-2xl p-6 shadow-xl mb-10">
                {{-- Background Decoration --}}
                <svg class="absolute right-0 top-0 opacity-10 h-full w-auto" fill="currentColor" viewBox="0 0 200 200">
                    <path
                        d="M40,-62.7C52.2,-54.5,62.5,-43.9,68.9,-31.4C75.4,-19,78.1,-4.7,76.1,9.1C74.1,22.9,67.4,36.2,57.5,46.5C47.6,56.8,34.5,64.1,20.4,68.1C6.3,72.1,-8.8,72.8,-23.1,68.5C-37.4,64.2,-50.9,54.8,-60.1,42.5C-69.3,30.2,-74.3,15.1,-74.1,0.1C-73.9,-14.9,-68.6,-29.8,-59.1,-41.8C-49.6,-53.8,-35.9,-62.9,-22.4,-68.9C-8.9,-74.9,4.4,-77.8,17.4,-74.4C30.4,-71,43,-61.2,40,-62.7Z"
                        transform="translate(100 100)" />
                </svg>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-right">
                        <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">نتيجة السؤال
                            ({{ $studentQuestionSelection->position }})</p>
                        <h3 class="text-4xl font-black">
                            {{ number_format($displayAverage, 2) }}
                        </h3>
                    </div>

                    @if ($studentQuestionSelection->is_passed == 0)
                        <div
                            class="bg-red-500/30 backdrop-blur-md border border-red-300 px-4 py-2 rounded-lg text-sm font-bold">
                            ⚠️ تم تصفير درجة السؤال
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Remaining Judges --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
            <h3 class="font-semibold text-yellow-700 mb-2">
                المحكمون المتبقون
            </h3>

            @forelse ($remainingJudges as $committeeUser)
                <p class="text-yellow-600">⏳ {{ $committeeUser->user->name }}</p>
            @empty
                <p class="text-green-700 font-semibold">
                    تم اكتمال تقييم جميع المحكمين
                </p>
            @endforelse
        </div>

        {{-- Navigation --}}
        {{-- Remaining Judges Alert --}}
        @if (!$isQuestionDone)
            <div class="bg-yellow-50 border-r-4 border-yellow-400 p-4 mb-6 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm leading-5 font-medium text-yellow-800">
                            بانتظار اكتمال التقييم
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="mb-4 mt-8 flex justify-center gap-4">

            @if ($nextUrl)
                {{-- CASE: Everyone finished -> Show Next Button --}}
                <a href="{{ $nextUrl }}"
                    class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition transform hover:scale-105 flex items-center gap-2">
                    {{ $buttonText }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            @else
                {{-- CASE: Waiting -> Show Refresh Button --}}
                <button onclick="window.location.reload()"
                    class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    تحديث الصفحة
                </button>
            @endif

        </div>



        {{-- Judges Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @foreach ($evaluationsByJudge as $judgeId => $evaluations)
                @php
                    $judge = $evaluations->first()->judge;
                    $note = $notesByJudge[$judgeId]->note ?? null;
                    $judgeTotal = $evaluations->sum(fn($ev) => $ev->element->max_score - $ev->reduct_point);
                    $judgeTotals[] = $judgeTotal;
                @endphp

                <div class="bg-white border rounded-xl overflow-hidden shadow-sm flex flex-col">
                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                        <span class="font-bold text-indigo-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                            </svg>
                            {{ $judge->name }}
                        </span>
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-md font-bold">تقييم
                            المحكم</span>
                    </div>

                    <div class="p-4 flex-grow">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-gray-400 border-b">
                                    <th class="text-right pb-2 font-normal">العنصر</th>
                                    <th class="text-center pb-2 font-normal">الخصم</th>
                                    <th class="text-left pb-2 font-normal">الصافي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($evaluations as $evaluation)
                                    @php
                                        $max = $evaluation->element->max_score;
                                        $final = $max - $evaluation->reduct_point;
                                    @endphp
                                    <tr>
                                        <td class="py-2 text-gray-700 font-medium">{{ $evaluation->element->title }}
                                        </td>
                                        <td class="py-2 text-center text-red-500 font-mono">
                                            -{{ number_format($evaluation->reduct_point, 2) }}</td>
                                        <td class="py-2 text-left font-bold text-gray-900">
                                            {{ number_format($final, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer for Judge Note & Total --}}
                    <div class="p-4 bg-gray-50 border-t">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600 font-bold">المجموع :</span>
                            <span class="text-xl font-black text-indigo-600">{{ number_format($judgeTotal, 2) }}</span>
                        </div>

                        <div class="text-xs text-gray-600 italic bg-white p-2 rounded border border-gray-200">
                            <span class="font-bold block not-italic text-gray-400 mb-1">ملاحظة المحكم:</span>
                            {{ $note ?? 'لا توجد ملاحظات مسجلة' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


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
</x-app-layout>
