<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <h2 class="text-xl font-bold mb-6">
            نتيجة تقييمك
        </h2>

        {{-- Judge scores for current user --}}
        <div class="bg-white shadow rounded p-4 mb-6">
            <h3 class="font-semibold mb-2">نتيجة تقييمك</h3>
            @foreach ($studentQuestionSelection->judgeEvaluations as $evaluation)
                <div class="flex justify-between border-b py-2">
                    <span>{{ $evaluation->element->title }}</span>
                    <span class="font-semibold text-red-600">
                        -{{ $evaluation->reduct_point }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Completed judges --}}
        <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
            <h3 class="font-semibold text-green-700 mb-2">
                المحكمون الذين أكملوا التقييم
            </h3>

            @forelse ($completedJudges as $judge)
                <div class="mb-2">
                    <p class="text-green-600 font-semibold">✔ {{ $judge->name }}</p>

                    {{-- Show judge note if exists --}}
                    @php
                        $note = \App\Models\JudgeNote::where(
                            'student_question_selection_id',
                            $studentQuestionSelection->id,
                        )
                            ->where('judge_id', $judge->id)
                            ->value('note');
                    @endphp

                    @if ($note)
                        <p class="text-gray-700 text-sm border-l-2 border-green-400 pl-2 mt-1">
                            {{ $note }}
                        </p>
                    @else
                        <p class="text-gray-400 text-sm italic mt-1">لم يضف ملاحظة</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">لا يوجد بعد</p>
            @endforelse
        </div>

        {{-- Remaining judges --}}
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

        {{-- Status message --}}
        <div class="mb-4">
            @if (!$studentQuestionSelection->done)
                <p class="text-yellow-600 font-semibold">
                    بانتظار بقية المحكمين لإنهاء التقييم...
                </p>
            @else
                <p class="text-green-600 font-bold">
                    اكتمل تقييم جميع المحكمين.
                </p>
            @endif
        </div>

        {{-- Next question button --}}
        <a href="{{ route('student.show_final_result', $studentQuestionSelection->competition_id) }}"
            class="px-6 py-2 rounded text-white font-bold transition
       {{ $studentQuestionSelection->done
           ? 'bg-green-600 hover:bg-green-700'
           : 'bg-gray-400 cursor-not-allowed pointer-events-none' }}">
            السؤال التالي
        </a>


    </div>

    {{-- Auto-enable next question button when all judges done --}}
    <script>
        const selectionId = {{ $studentQuestionSelection->id }};
        const button = document.getElementById('next-question-btn');

        if (button.hasAttribute('disabled')) {
            const interval = setInterval(() => {
                fetch(`/student/evaluation-status/${selectionId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.done) {
                            button.removeAttribute('disabled');
                            button.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            button.classList.add('bg-green-600', 'hover:bg-green-700');
                            clearInterval(interval);
                        }
                    });
            }, 5000);
        }
    </script>
</x-app-layout>
