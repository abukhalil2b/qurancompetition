<x-app-layout>

    {{-- Inline styles kept minimal and semantic --}}
    <style>
        .btn-plus-big,
        .btn-minus-big {
            padding: 10px 15px;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease, background-color 0.2s ease;
            cursor: pointer;
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
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
    </style>

    <div class="mb-4 px-4 mt-1" dir="rtl" x-data="evaluationPage({{ $evaluationElements }})">

        {{-- Evaluation Form --}}
        <form method="POST" action="{{ route('student.save_evaluation') }}" class="mt-2">
            @csrf

            <input type="hidden" name="student_question_selection_id" value="{{ $studentQuestionSelection->id }}">

            <div class="space-y-3">

                {{-- Question Header --}}
                <div class="bg-indigo-500 px-4 py-3  text-white flex items-center justify-between rounded-lg gap-1">
                    <div class="text-xs flex items-center gap-2 bg-blue-800 p-2 rounded">
                        {{ $studentQuestionSelection->position }}
                    </div>
                    <span>
                        {!! nl2br($studentQuestionSelection->question->content) !!}
                    </span>

                    <div class="text-xs flex items-center gap-2 bg-blue-800 p-2 rounded">
                        <span>إجمالي الخصم:</span>
                        <span x-text="totalScore" class="font-bold text-lg"></span>
                    </div>
                </div>

                {{-- Evaluation Elements --}}
                <div class="space-y-2">

                    @foreach ($evaluationElements as $element)
                        <div class="p-1 bg-white border rounded flex justify-between items-center"
                            x-data="{ id: {{ $element->id }}, max: {{ $element->max_score }} }">
                            {{-- Element Info --}}
                            <div class="flex flex-col gap-1">
                                <div class="text-gray-800 font-bold">
                                    {{ $element->title }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    الوزن: {{ $element->max_score }}
                                </div>
                            </div>

                            {{-- Controls --}}
                            <div class="flex items-center gap-3">

                                {{-- Deduct 1 --}}
                                <button type="button" @click="decrease(id, 1, max)" class="btn-minus-big">
                                    -1
                                </button>

                                {{-- Deduct 0.5 --}}
                                <button type="button" @click="decrease(id, 0.5, max)" class="btn-minus-big">
                                    -0.5
                                </button>

                                {{-- Current Penalty --}}
                                <input type="number"
                                    class="score-input-big border rounded-lg w-20 text-center font-bold"
                                    x-model="scores[id]" readonly>

                                {{-- Undo 0.5 --}}
                                <button type="button" @click="increase(id, 0.5)" class="btn-plus-big">
                                    +0.5
                                </button>

                                {{-- Undo 1 --}}
                                <button type="button" @click="increase(id, 1)" class="btn-plus-big">
                                    +1
                                </button>

                                {{-- Hidden submission value --}}
                                <input type="hidden" :name="'elements[' + id + ']'" x-model="scores[id]">
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Judge Note --}}
                <textarea name="note" rows="4" placeholder="ملاحظة المحكم (اختياري)"
                    class="w-full p-3 border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>

                @if ($isJudgeLeader)
                    <div class="mb-6 flex items-center justify-between bg-red-50 p-4 rounded-xl border border-red-100">
                        <div class="flex flex-col">
                            <label for="student_lost_question" class="text-sm font-bold text-red-800 cursor-pointer">
                                إلغاء السؤال
                            </label>
                            <span class="text-xs text-red-600 mt-1">تفعيل هذا الخيار يعني حصول المتسابق على صفر في هذا
                                السؤال.</span>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="student_lost_question" id="student_lost_question"
                                value="1" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600">
                            </div>
                        </label>
                    </div>
                @endif
                {{-- Save Button --}}
                <button
                    class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-xl flex items-center justify-center gap-3 hover:bg-indigo-700 hover:scale-105 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ نتيجة السؤال
                </button>

            </div>
        </form>

        <div class="py-4">
            ملحوظة: {{ $stage->title }}
        </div>
        <a class=" w-full px-6 py-3 bg-purple-600 text-white font-bold rounded shadow-xl flex items-center justify-center gap-3 hover:bg-purple-700"
            href="{{ route('student.show_evaluation', $studentQuestionSelection->id) }}">مشاهدة تقييم اللجنة</a>

    </div>

    {{-- Alpine Logic --}}
    <script>
        function evaluationPage(elements) {
            return {
                scores: {},
                firstElementId: null,

                init() {
                    elements.forEach((el, index) => {
                        this.scores[el.id] = 0;
                        if (index === 0) {
                            this.firstElementId = el.id;
                        }
                    });
                },

                get totalScore() {
                    return Object.values(this.scores)
                        .reduce((a, b) => a + parseFloat(b), 0)
                        .toFixed(1);
                },

                decrease(id, amount, max) {
                    let next = parseFloat((this.scores[id] - amount).toFixed(1));

                    // Special hard-fail rule for first element
                    if (id === this.firstElementId && next <= -4) {
                        this.scores[id] = -max;
                        return;
                    }

                    this.scores[id] = next;
                },

                increase(id, amount) {
                    // Undo deductions only (never positive)
                    if (this.scores[id] + amount <= 0) {
                        this.scores[id] = parseFloat(
                            (this.scores[id] + amount).toFixed(1)
                        );
                    }
                }
            };
        }
    </script>

</x-app-layout>
