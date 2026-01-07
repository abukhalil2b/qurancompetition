<x-app-layout>
<div class="max-w-6xl mx-auto py-8">

    <h2 class="text-2xl font-bold mb-6">النتيجة النهائية للطالب: {{ $student->name }}</h2>

    @foreach ($questions as $qIndex => $selection)
        <div class="bg-white border rounded-xl shadow p-5 mb-6">

            <h3 class="font-bold text-lg mb-2">السؤال {{ $qIndex + 1 }}: {{ $selection->question->content }}</h3>

            {{-- Table header --}}
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-3 py-2">العنصر</th>
                        <th class="border px-3 py-2">الحد الأقصى</th>
                        <th class="border px-3 py-2">كل محكم</th>
                        <th class="border px-3 py-2">المعدل</th>
                        <th class="border px-3 py-2">النتيجة</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Group judge evaluations by element
                        $elementGroups = $selection->judgeEvaluations->groupBy('evaluation_element_id');
                    @endphp

                    @foreach ($elementGroups as $elementId => $evaluations)
                        @php
                            $element = $evaluations->first()->element;
                            $maxScore = $element->max_score;

                            $scores = $evaluations->map(function($ev) use($maxScore) {
                                return $maxScore - $ev->reduct_point;
                            });

                            $average = $scores->avg();
                            $finalResult = $average; // For 'question' scope, use directly. For 'competition', can sum later
                        @endphp
                        <tr>
                            <td class="border px-3 py-2 font-semibold">{{ $element->title }}</td>
                            <td class="border px-3 py-2">{{ $maxScore }}</td>
                            <td class="border px-3 py-2">
                                @foreach ($evaluations as $ev)
                                    <div>{{ $ev->judge->name }}: {{ $maxScore }} - {{ $ev->reduct_point }} = {{ $maxScore - $ev->reduct_point }}</div>
                                @endforeach
                            </td>
                            <td class="border px-3 py-2 font-bold">{{ number_format($average, 2) }}</td>
                            <td class="border px-3 py-2 font-bold text-green-700">{{ number_format($finalResult, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    @endforeach

    {{-- Total score --}}
    @php
        $totalScore = $questions->sum(function($selection) {
            return $selection->judgeEvaluations
                ->groupBy('evaluation_element_id')
                ->map(function($evaluations) {
                    $element = $evaluations->first()->element;
                    $maxScore = $element->max_score;
                    $scores = $evaluations->map(fn($ev) => $maxScore - $ev->reduct_point);
                    return $scores->avg();
                })->sum();
        });
    @endphp

    <div class="bg-indigo-50 p-5 rounded-xl shadow text-xl font-bold text-indigo-800">
        النتيجة الإجمالية للطالب: {{ number_format($totalScore, 2) }}
    </div>

</div>
</x-app-layout>
