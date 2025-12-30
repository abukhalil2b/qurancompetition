<x-app-layout>
    <style>
        .btn-plus-big,
        .btn-minus-big {
            padding: 10px 15px;
            /* أزرار كبيرة */
            border-radius: 0.5rem;
            /* زوايا ناعمة */
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.15s ease, background-color 0.2s ease;
            cursor: pointer;
        }

        /* زر الإضافة */
        .btn-plus-big {
            background-color: #16a34a;
            /* أخضر */
        }

        .btn-plus-big:hover {
            background-color: #15803d;
            transform: scale(1.05);
        }

        /* زر الطرح */
        .btn-minus-big {
            background-color: #dc2626;
            /* أحمر */
        }

        .btn-minus-big:hover {
            background-color: #b91c1c;
            transform: scale(1.05);
        }

        /* حقل النتيجة */
        .score-input-big {
            padding: 0.5rem;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
    </style>
    <div class="mb-4 px-4 mt-1" x-data="evaluationPage({{ $evaluationElements }})" dir="rtl">
        <!-- Evaluation -->
        <form method="POST" action="{{ route('student.save_evaluation') }}" class="mt-2">
            @csrf
            <input type="hidden" name="student_question_selection_id" value="{{ $studentQuestionSelection->id }}">
            <div class="space-y-2">
                <!-- Question Header -->
                <div class="bg-indigo-600 px-4 py-3 font-semibold text-white flex items-center justify-between">

                    <span class="flex items-center gap-2">

                        {{ $studentQuestionSelection->question->content }}
                    </span>

                    <div class="text-sm flex items-center gap-2">
                        <span>المجموع:</span>
                        <span x-text="totalScore" class="font-bold text-lg"></span>
                    </div>
                </div>
                <!-- Elements -->
                <div class="space-y-2">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($evaluationElements as $element)
                            <div class="p-2 bg-white border rounded-xl  flex  justify-between items-center">

                                <!-- Title -->
                                <div class="flex gap-3 items-center py-3">
                                    <div class="text-gray-800 font-bold">{{ $element->title }}</div>
                                    <div class="text-xs text-gray-500">الحد الأقصى: {{ $element->max_score }}</div>
                                </div>

                                <!-- Score Controls -->
                                <div class="flex items-center gap-3" x-data="{ id: {{ $element->id }}, max: {{ $element->max_score }} }">

                                    <!-- Minus 1 -->
                                    <button type="button" @click="decrease(id, 1)" class="btn-minus-big">
                                        -1
                                    </button>

                                    <!-- Minus 0.5 -->
                                    <button type="button" @click="decrease(id, 0.5)" class="btn-minus-big">
                                        -0.5
                                    </button>

                                    <!-- Current Score -->
                                    <input type="number"
                                        class="score-input-big border rounded-lg w-20 text-center font-bold"
                                        x-model="scores[id]" readonly>

                                    <!-- Plus 0.5 -->
                                    <button type="button" @click="increase(id, 0.5, max)" class="btn-plus-big">
                                        +0.5
                                    </button>

                                    <!-- Plus 1 -->
                                    <button type="button" @click="increase(id, 1, max)" class="btn-plus-big">
                                        +1
                                    </button>

                                    <input type="hidden" :name="'elements[' + id + ']'" x-model="scores[id]">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Save -->
                <div class="mt-8 p-4 bg-white rounded-lg border shadow-lg">
                    <textarea name="note" rows="4" placeholder="ملحوظة المحكم (اختياري)"
                        class="w-full p-3 border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
                </div>
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
    </div>
    <script>
        function evaluationPage(elements) {
            return {
                scores: {},

                init() {
                    elements.forEach(el => {
                        this.scores[el.id] = el.max_score;
                    });
                },

                get totalScore() {
                    return Object.values(this.scores)
                        .reduce((a, b) => a + parseFloat(b), 0)
                        .toFixed(1);
                },

                increase(id, amount, max) {
                    if (this.scores[id] + amount <= max)
                        this.scores[id] = parseFloat((this.scores[id] + amount).toFixed(1));
                },

                decrease(id, amount) {
                    if (this.scores[id] - amount >= 0)
                        this.scores[id] = parseFloat((this.scores[id] - amount).toFixed(1));
                }
            };
        }
    </script>
</x-app-layout>
