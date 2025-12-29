<x-app-layout>
    <style>
        .btn-plus-big,
        .btn-minus-big {
            padding: 0.75rem 1.5rem;
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
            font-size: 1.25rem;
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
        }
    </style>

    <div class="max-w-4xl mx-auto py-8 px-4" x-data="evaluationPage({{ $evaluationElements }})" dir="rtl">

        <div class="bg-white rounded-xl shadow p-6">

            <!-- Header -->
            <header class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-2 text-indigo-700">
                        تقييم المتسابق
                    </h1>

                    <div class="text-sm mt-2 space-y-1">

                        <span class="font-medium">{{ $studentQuestionSelection->stage->title }} -
                            {{ $studentQuestionSelection->committee->title }} -
                            {{ $studentQuestionSelection->center->title }}</span>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-sm flex items-center gap-1 justify-end">
                        <span class="text-gray-600">التاريخ:</span>
                        <span>{{ date('Y-m-d') }}</span>
                    </div>
                   <button 
    @click="showStudentInfo = !showStudentInfo"
    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors"
>
    <span x-show="!showStudentInfo">عرض بيانات الطالب</span>
    <span x-show="showStudentInfo">إخفاء بيانات الطالب</span>
</button>
                </div>
            </header>

            <!-- Student Info -->
            <section x-show="showStudentInfo" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-6 border rounded-lg bg-indigo-50">

                <!-- العمود الأول -->
                <!-- الاسم والرقم المدني -->
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <span class="text-sm text-gray-600">اسم المتسابق</span>
                    <span
                        class="font-bold text-lg text-indigo-700">{{ $studentQuestionSelection->student->name }}</span>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <span class="text-sm text-gray-600">الرقم المدني</span>
                    <span class="font-semibold">{{ $studentQuestionSelection->student->national_id }}</span>
                </div>

                <!-- الجنسية والجنس -->
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <span class="text-sm text-gray-600">الجنسية</span>
                    <span class="font-semibold">{{ $studentQuestionSelection->student->nationality }}</span>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <span class="text-sm text-gray-600">الجنس</span>
                    <span class="font-semibold">{{ __($studentQuestionSelection->student->gender) }}</span>
                </div>

                <!-- المستوى -->
                <div class="bg-white shadow-sm rounded-lg p-4 flex flex-col">
                    <span class="text-sm text-gray-600">المستوى</span>
                    <span class="text-lg font-semibold text-indigo-700">{{ $studentQuestionSelection->level }}</span>
                </div>

            </section>
        </div>

        <!-- Evaluation -->
        <form method="POST" action="{{ route('student.save_evaluation') }}" class="mt-2">
            @csrf

            <input type="hidden" name="student_question_selection_id" value="{{ $studentQuestionSelection->id }}">

            <div class="space-y-3">

                <div class="border rounded-lg overflow-hidden ">

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
                    <div class="p-2 space-y-2">
                        <div class="grid grid-cols-1 gap-2">
                            @foreach ($evaluationElements as $element)
                                <div class="p-2 bg-white border rounded-xl  flex flex-col justify-between items-center">

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
                                            class="score-input-big border rounded-lg w-20 text-center font-bold text-lg"
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
                </div>

                <!-- Save -->
                <div class="mt-8 p-4 bg-white rounded-lg border shadow-lg">
                    <button
                        class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-xl flex items-center justify-center gap-3 hover:bg-indigo-700 hover:scale-105 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        حفظ نتيجة السؤال
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        function evaluationPage(elements) {
            return {
            showStudentInfo: false,
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
