<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الدرجات | {{ $student->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans">
    <div class="max-w-4xl mx-auto py-10 px-4">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $student->name }}</h1>
                <p class="text-gray-500 text-lg">{{ $student->branch_name }} - {{ $student->level }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('result.show', $competition->id) }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" class="transform rotate-180"></path>
                    </svg>
                    العودة للنتيجة النهائية
                </a>

                {{-- Finalize Button --}}
                @if ($competition->student_status !== 'finish_competition')
                    @if ($isJudgeLeader)
                        <form action="{{ route('competition.finalize', $competition->id) }}" method="POST"
                            class="inline-block mr-4" onsubmit="return confirm('هل أنت متأكد من اعتماد النتيجة النهائية؟');">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                اعتماد النتيجة
                            </button>
                        </form>
                    @endif
                @else
                    <div
                        class="inline-block mr-4 px-4 py-2 bg-green-100 text-green-800 rounded-lg border border-green-200 font-bold">
                        تم اعتماد النتيجة
                    </div>
                @endif

            </div>
        </div>

        {{-- Main Score Table --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-l from-indigo-800 to-blue-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 ml-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    تفاصيل الدرجات حسب المحكم
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                            <th class="py-4 px-6 font-semibold w-1/3">المحكم</th>
                            <th class="py-4 px-6 font-semibold text-center">أداء الحفظ</th>
                            @if ($competition->student->level === 'حفظ وتفسير')
                                <th class="py-4 px-6 font-semibold text-center">درجة التفسير</th>
                            @endif
                            <th class="py-4 px-6 font-semibold text-center">المجموع الكلي</th>
                            <th class="py-4 px-6 font-semibold text-center">النسبة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($judgeScores as $score)
                            <tr class="hover:bg-blue-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold ml-3 text-lg">
                                            {{ mb_substr($score['judge_name'], 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $score['judge_name'] }}</div>
                                            <div class="text-xs text-gray-400">لجنة التحكيم</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block py-1 px-3 rounded-full bg-gray-100 text-gray-700 font-bold">
                                        {{ number_format($score['memorization_score'], 1) }}
                                    </span>
                                </td>
                                @if ($competition->student->level === 'حفظ وتفسير')
                                    <td class="py-4 px-6 text-center">
                                        <span
                                            class="inline-block py-1 px-3 rounded-full bg-orange-100 text-orange-700 font-bold">
                                            {{ number_format($score['tafseer_score'], 1) }}
                                        </span>
                                    </td>
                                @endif
                                <td class="py-4 px-6 text-center">
                                    <span class="text-xl font-bold text-indigo-700">
                                        {{ number_format($score['total_score'], 1) }}
                                    </span>
                                    <span class="text-sm text-gray-400">/ {{ $score['max_score'] }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @php
                                        $percentage = ($score['total_score'] / $score['max_score']) * 100;
                                        $colorClass = $percentage >= 90 ? 'text-green-600' : ($percentage >= 80 ? 'text-blue-600' : 'text-red-500');
                                    @endphp
                                    <div class="font-bold {{ $colorClass }}">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($judgeScores->isEmpty())
                <div class="p-10 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg">لا توجد تفاصيل درجات متاحة لهذا المتسابق.</p>
                </div>
            @endif
        </div>

        {{-- Footer Note --}}
        <div class="mt-6 text-center text-sm text-gray-400">
            * الدرجات تعتمد على الخصومات المسجلة من كل محكم بشكل فردي.
        </div>
    </div>
</body>

</html>