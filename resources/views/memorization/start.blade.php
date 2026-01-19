<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم المتسابق | {{ $studentQuestionSelection->position }}</title>

    {{-- Vite Assets for Tailwind and Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f3f4f6;
        }

        .btn-plus-big,
        .btn-minus-big {
            padding: 12px 20px;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            cursor: pointer;
            touch-action: manipulation;
        }

        .btn-plus-big {
            background-color: #16a34a;
        }

        .btn-plus-big:hover {
            background-color: #15803d;
            transform: scale(1.05);
        }

        .btn-minus-big {
            background-color: #dc2626;
        }

        .btn-minus-big:hover {
            background-color: #b91c1c;
            transform: scale(1.05);
        }

        .score-input-big {
            padding: 0.5rem;
            background-color: #fdfdfd;
            border: 2px solid #e5e7eb;
            outline: none;
        }

        /* Custom scrollbar for the right side for a cleaner look */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased overflow-hidden">

    <div class="h-screen flex flex-col md:flex-row" x-data="evaluationPage({{ $evaluationElements }}, {{ $oldScores->toJson() }})">

        {{-- LEFT SIDE: Question Header (Approx 40-50% width) --}}
        <div
            class="w-full md:w-5/12 bg-indigo-700 text-white p-6 flex flex-col justify-center items-center text-center shadow-2xl z-10">
            <div class="mb-6">
                <span
                    class="bg-indigo-900 h-20 w-20 flex items-center justify-center rounded-2xl font-black text-3xl mx-auto mb-4 shadow-inner">
                    {{ $studentQuestionSelection->position }}
                </span>
                <h2 class="text-2xl md:text-3xl font-bold leading-relaxed">
                    {!! nl2br(e($studentQuestionSelection->question->content)) !!}
                </h2>
            </div>

            <div class="bg-indigo-900/50 p-6 rounded-2xl border border-indigo-500 w-full max-w-sm">
                <span class="text-lg block mb-1 opacity-80">إجمالي الخصم الحالي</span>
                <span x-text="totalScore" class="font-black text-6xl tracking-tight"></span>
            </div>

            <div class="mt-8 text-indigo-200 text-sm">
                المرحلة الحالية: {{ $stage->title }}
            </div>
        </div>

        {{-- RIGHT SIDE: Evaluation Elements (Approx 50-60% width) --}}
        <div class="w-full md:w-7/12 h-full overflow-y-auto custom-scroll bg-gray-100 p-4 md:p-8">
            <form method="POST" action="{{ route('memorization.store') }}">
                @csrf
                <input type="hidden" name="student_question_selection_id" value="{{ $studentQuestionSelection->id }}">

                <div class="max-w-3xl mx-auto space-y-6 pb-20">

                    {{-- Evaluation Elements --}}
                    <div class="space-y-4">
                        @foreach ($evaluationElements as $element)
                            <div
                                class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col lg:flex-row justify-between items-center gap-4">

                                {{-- Element Info --}}
                                <div class="flex flex-col text-center lg:text-right w-full lg:w-1/3">
                                    <div class="text-gray-900 font-extrabold text-lg">
                                        {{ $element->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 font-bold">
                                        الوزن: {{ $element->max_score }}
                                    </div>
                                </div>

                                {{-- Controls --}}
                                <div class="flex items-center gap-2 lg:w-2/3 justify-center lg:justify-end">
                                    <button type="button"
                                        @click="decrease({{ $element->id }}, 1, {{ $element->max_score }})"
                                        class="btn-minus-big">-1</button>
                                    <button type="button"
                                        @click="decrease({{ $element->id }}, 0.5, {{ $element->max_score }})"
                                        class="btn-minus-big">-0.5</button>

                                    <input type="number"
                                        class="score-input-big rounded-xl w-20 text-center font-black text-xl text-red-600"
                                        x-model="scores[{{ $element->id }}]" readonly>

                                    <button type="button" @click="increase({{ $element->id }}, 0.5)"
                                        class="btn-plus-big">+0.5</button>
                                    <button type="button" @click="increase({{ $element->id }}, 1)"
                                        class="btn-plus-big">+1</button>

                                    <input type="hidden" name="elements[{{ $element->id }}]"
                                        x-model="scores[{{ $element->id }}]">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Judge Note --}}
                    <div class="space-y-2">
                        <label class="text-gray-600 font-bold mr-2">ملاحظات المحكم</label>
                        <div class="bg-white p-2 rounded-xl border shadow-sm">
                            <textarea name="note" rows="3" placeholder="أضف ملاحظاتك هنا..."
                                class="w-full p-4 border-none rounded-lg resize-none focus:ring-0 text-lg">{{ old('note', $oldNote) }}</textarea>
                        </div>
                    </div>

                    {{-- Leader Controls --}}
                    @if ($isJudgeLeader)
                        <div class="flex items-center justify-between bg-red-50 p-5 rounded-2xl border border-red-200">
                            <div class="flex flex-col ml-4">
                                <label for="student_lost_question"
                                    class="text-lg font-bold text-red-800 cursor-pointer">إلغاء السؤال</label>
                                <span class="text-xs text-red-600">نتيجة السؤال لن تحسب في الاعتماد النهائي</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="student_lost_question" id="student_lost_question"
                                    value="1" class="sr-only peer"
                                    {{ !$studentQuestionSelection->is_passed ? 'checked' : '' }}>
                                <div
                                    class="w-14 h-7 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-red-600 after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all">
                                </div>
                            </label>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button type="submit"
                            class="px-6 py-4 bg-indigo-600 text-white font-black text-xl rounded-xl shadow-lg flex items-center justify-center gap-3 hover:bg-indigo-700 transition active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            حفظ التقييم
                        </button>
                        <a href="{{ route('memorization.show', $studentQuestionSelection->id) }}"
                            class="px-6 py-4 bg-white border-2 border-indigo-600 text-indigo-600 font-bold text-lg rounded-xl shadow flex items-center justify-center gap-3 hover:bg-indigo-50 transition">
                            عرض الملخص
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function evaluationPage(elements, oldScores) {
            return {
                scores: {},
                firstElementId: null,
                init() {
                    elements.forEach((el, index) => {
                        if (oldScores && oldScores.hasOwnProperty(el.id)) {
                            this.scores[el.id] = parseFloat(oldScores[el.id]) * -1;
                        } else {
                            this.scores[el.id] = 0;
                        }
                        if (index === 0) this.firstElementId = el.id;
                    });
                },
                get totalScore() {
                    let total = Object.values(this.scores).reduce((a, b) => a + parseFloat(b), 0);
                    return total.toFixed(1);
                },
                decrease(id, amount, max) {
                    let next = parseFloat((this.scores[id] - amount).toFixed(1));

                    if (next < -max) {
                        next = -max;
                    }

                    this.scores[id] = next;
                },
                increase(id, amount) {
                    let next = parseFloat((this.scores[id] + amount).toFixed(1));
                    this.scores[id] = next <= 0 ? next : 0;
                }
            };
        }
    </script>
</body>

</html>
