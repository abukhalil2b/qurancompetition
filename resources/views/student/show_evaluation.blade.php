<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-xl font-bold mb-4"> نتيجة السؤال: {{ $studentQuestionSelection->position }}</h1>
        <h2 class="mb-4">

            {!! nl2br($studentQuestionSelection->question->content) !!}
        </h2>

        @php
            $evaluationsByJudge = $studentQuestionSelection->judgeEvaluations->groupBy('judge_id');

            $notesByJudge = $studentQuestionSelection->judgeNotes->keyBy('judge_id');

            $judgeTotals = [];
        @endphp

        {{-- Judges Results --}}
        <div class="bg-white shadow rounded p-5 mb-6">
            <h3 class="font-semibold text-lg mb-4 text-green-700">
                نتائج المحكمين
            </h3>

            @foreach ($evaluationsByJudge as $judgeId => $evaluations)
                @php
                    $judge = $evaluations->first()->judge;
                    $note = $notesByJudge[$judgeId]->note ?? null;

                    // Calculate judge total for this question
                    $judgeTotal = $evaluations->sum(function ($evaluation) {
                        return $evaluation->element->max_score - $evaluation->reduct_point;
                    });

                    $judgeTotals[] = $judgeTotal;
                @endphp

                <div class="border rounded-lg p-4 mb-4">
                    <p class="font-bold text-indigo-700 mb-2">
                        ✔ {{ $judge->name }}
                    </p>

                    {{-- Element scores --}}
                    @foreach ($evaluations as $evaluation)
                        @php
                            $max = $evaluation->element->max_score;
                            $final = $max - $evaluation->reduct_point;
                        @endphp

                        <div class="flex justify-between border-b py-1 text-sm">
                            <span>{{ $evaluation->element->title }}</span>
                            <span class="font-semibold">
                                {{ number_format($final, 2) }} / {{ $max }}
                            </span>
                        </div>
                    @endforeach

                    {{-- Judge total --}}
                    <div class="flex justify-between mt-3 font-bold text-green-700">
                        <span>مجموع المحكم</span>
                        <span>{{ number_format($judgeTotal, 2) }}</span>
                    </div>

                    {{-- Judge note --}}
                    @if ($note)
                        <p class="mt-3 text-sm text-gray-700 border-l-4 border-indigo-400 pl-2">
                            {{ $note }}
                        </p>
                    @else
                        <p class="mt-3 text-sm text-gray-400 italic">
                            لا توجد ملاحظة
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Question Average --}}
        @if (count($judgeTotals) > 0)
            @php
                $questionAverage = collect($judgeTotals)->avg();
            @endphp

            <div class="bg-indigo-50 border border-indigo-200 rounded p-4 mb-6">
                <p class="font-bold text-indigo-700 text-lg">
                    متوسط نتيجة السؤال (جميع المحكمين):
                    {{ number_format($questionAverage, 2) }}
                </p>
            </div>
        @endif


        @if ($studentQuestionSelection->is_passed == 0)
            <div class="mb-6 flex items-center justify-between bg-red-50 p-4 rounded-xl border border-red-100">
                <div class="flex flex-col">
                    <label for="student_lost_question" class="text-sm font-bold text-red-800 cursor-pointer">
                        إلغاء السؤال
                    </label>
                    <span class="text-xs text-red-600 mt-1">
                        بغض النظر عن النتيجة فقد تقرر إلغاء السؤال
                    </span>
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
        @if ($studentQuestionSelection->done)
            @if ($nextQuestion)
                <a href="{{ route('student.start_evaluation', $nextQuestion->id) }}"
                    class="inline-block bg-indigo-600 text-white px-5 py-2 rounded font-semibold">
                    {{ $buttonText }}
                </a>
            @else
                <a href="{{ route('student.show_final_result', $competition->id) }}"
                    class="inline-block bg-green-600 text-white px-5 py-2 rounded font-semibold">
                    {{ $buttonText }}
                </a>
            @endif
        @endif

    </div>
</x-app-layout>
