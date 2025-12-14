<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>لوحة تقييم المتسابق - تفاعلي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .score-input {
            width: 4rem;
            text-align: center;
            -moz-appearance: textfield;
        }

        .score-input::-webkit-outer-spin-button,
        .score-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow p-6">

            <header class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-edit text-indigo-600"></i>
                        لوحة تقييم المتسابق
                    </h1>
                </div>
                <div class="text-right space-y-1">
                    <div class="text-sm flex items-center gap-1 justify-end">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                        التاريخ: <span class="font-medium">20-11-2025</span>
                    </div>
                </div>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 border rounded-lg bg-indigo-50">
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-100 p-2 rounded-lg">
                        <i class="fas fa-user-circle text-indigo-600"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">اسم المتسابق</div>
                        <div class="text-lg font-semibold" id="student-name">خالد بن سعيد</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-100 p-2 rounded-lg">
                        <i class="fas fa-book-open text-indigo-600"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600"> المستوى</div>
                    </div>
                </div>
            </section>

            <div class="space-y-6">
                <div class="border rounded-lg overflow-hidden" data-question-id="1">
                    <div class="bg-indigo-50 px-4 py-3 font-semibold flex items-center gap-2 justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-question-circle text-indigo-600"></i>
                            السؤال الأول: اقرأ من قوله تعالى ... إلى قوله تعالى..
                        </span>
                        <div class="text-sm text-gray-600 flex items-center gap-1">
                            <i class="fas fa-star text-indigo-600"></i>
                            المجموع : <span class="font-bold text-lg text-indigo-700"
                                id="q1-judge1-total">17</span>
                        </div>
                    </div>

                    <div class="p-4 space-y-6">
                        <div data-judge-id="1">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="q1-judge1-elements">

                                <!-- التجويد -->
                                <div class="p-3 border rounded flex justify-between items-center bg-white shadow-sm">
                                    <div>
                                        <div class="text-sm text-gray-600">التجويد</div>
                                        <div class="text-xs text-gray-400">الحد الأقصى: 5</div>
                                    </div>
                                    <div class="flex items-center gap-1">

                                        <button class="score-adjust-btn text-red-500" data-action="-1"
                                            data-max="5" data-judge="1" data-element="tajweed">-1</button>

                                        <button class="score-adjust-btn text-red-500" data-action="-0.5"
                                            data-max="5" data-judge="1" data-element="tajweed">-0.5</button>

                                        <input type="number" class="score-input border rounded p-1"
                                            value="5" min="0" max="5" step="0.5"
                                            data-judge="1" data-element="tajweed" readonly>

                                        <button class="score-adjust-btn text-green-500" data-action="+0.5"
                                            data-max="5" data-judge="1" data-element="tajweed">+0.5</button>

                                        <button class="score-adjust-btn text-green-500" data-action="+1"
                                            data-max="5" data-judge="1" data-element="tajweed">+1</button>
                                    </div>
                                </div>

                                <!-- الحفظ -->
                                <div class="p-3 border rounded flex justify-between items-center bg-white shadow-sm">
                                    <div>
                                        <div class="text-sm text-gray-600">الحفظ</div>
                                        <div class="text-xs text-gray-400">الحد الأقصى: 5</div>
                                    </div>
                                    <div class="flex items-center gap-1">

                                        <button class="score-adjust-btn text-red-500" data-action="-1"
                                            data-max="5" data-judge="1" data-element="hifz">-1</button>

                                        <button class="score-adjust-btn text-red-500" data-action="-0.5"
                                            data-max="5" data-judge="1" data-element="hifz">-0.5</button>

                                        <input type="number" class="score-input border rounded p-1"
                                            value="5" min="0" max="5" step="0.5"
                                            data-judge="1" data-element="hifz" readonly>

                                        <button class="score-adjust-btn text-green-500" data-action="+0.5"
                                            data-max="5" data-judge="1" data-element="hifz">+0.5</button>

                                        <button class="score-adjust-btn text-green-500" data-action="+1"
                                            data-max="5" data-judge="1" data-element="hifz">+1</button>
                                    </div>
                                </div>

                                <!-- الأداء -->
                                <div class="p-3 border rounded flex justify-between items-center bg-white shadow-sm">
                                    <div>
                                        <div class="text-sm text-gray-600">الأداء</div>
                                        <div class="text-xs text-gray-400">الحد الأقصى: 3</div>
                                    </div>
                                    <div class="flex items-center gap-1">

                                        <button class="score-adjust-btn text-red-500" data-action="-1"
                                            data-max="3" data-judge="1" data-element="performance">-1</button>

                                        <button class="score-adjust-btn text-red-500" data-action="-0.5"
                                            data-max="3" data-judge="1" data-element="performance">-0.5</button>

                                        <input type="number" class="score-input border rounded p-1"
                                            value="2" min="0" max="3" step="0.5"
                                            data-judge="1" data-element="performance" readonly>

                                        <button class="score-adjust-btn text-green-500" data-action="+0.5"
                                            data-max="3" data-judge="1" data-element="performance">+0.5</button>

                                        <button class="score-adjust-btn text-green-500" data-action="+1"
                                            data-max="3" data-judge="1" data-element="performance">+1</button>
                                    </div>
                                </div>

                                <!-- التفسير -->
                                <div class="p-3 border rounded flex justify-between items-center bg-white shadow-sm">
                                    <div>
                                        <div class="text-sm text-gray-600">التفسير ومعاني المفردات</div>
                                        <div class="text-xs text-gray-400">الحد الأقصى: 5</div>
                                    </div>
                                    <div class="flex items-center gap-1">

                                        <button class="score-adjust-btn text-red-500" data-action="-1"
                                            data-max="5" data-judge="1" data-element="tafsir">-1</button>

                                        <button class="score-adjust-btn text-red-500" data-action="-0.5"
                                            data-max="5" data-judge="1" data-element="tafsir">-0.5</button>

                                        <input type="number" class="score-input border rounded p-1"
                                            value="5" min="0" max="5" step="0.5"
                                            data-judge="1" data-element="tafsir" readonly>

                                        <button class="score-adjust-btn text-green-500" data-action="+0.5"
                                            data-max="5" data-judge="1" data-element="tafsir">+0.5</button>

                                        <button class="score-adjust-btn text-green-500" data-action="+1"
                                            data-max="5" data-judge="1" data-element="tafsir">+1</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-white rounded-lg border shadow-lg">
                    <button onclick="saveResults()"
                        class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-xl flex items-center justify-center gap-3 hover:bg-indigo-700 transition-colors transform hover:scale-105">
                        <i class="fas fa-save fa-lg"></i>
                        إنهاء الصفحة وحفظ النتيجة النهائية
                    </button>
                </div>

            </div>

        </div>
    </div>

    <script>

        function calculateJudgeTotal(judgeId) {
            const inputs = document.querySelectorAll(`input[data-judge="${judgeId}"]`);
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            return total;
        }

        function updateJudge1Total() {
            const judge1Total = calculateJudgeTotal(1);
            document.getElementById('q1-judge1-total').textContent = judge1Total;
        }

        const scoreAdjustHandler = function () {
            const judgeId = this.getAttribute('data-judge');
            const element = this.getAttribute('data-element');
            const max = parseFloat(this.getAttribute('data-max'));
            const input = document.querySelector(
                `input[data-judge="${judgeId}"][data-element="${element}"]`
            );

            let currentValue = parseFloat(input.value) || 0;
            let change = parseFloat(this.getAttribute('data-action'));

            let newValue = currentValue + change;

            if (newValue > max) newValue = max;
            if (newValue < 0) newValue = 0;

            newValue = Math.round(newValue * 2) / 2;

            input.value = newValue;
            updateJudge1Total();
        };

        document.querySelectorAll('.score-adjust-btn').forEach(button => {
            button.addEventListener('click', scoreAdjustHandler);
        });

        updateJudge1Total();

        function saveResults() {
            const studentName = document.getElementById('student-name').textContent;
            const totalScore = document.getElementById('q1-judge1-total').textContent;

            alert(`تم حفظ نتائج المتسابق "${studentName}" بنجاح! الإجمالي: ${totalScore}`);
        }

    </script>

</body>

</html>
