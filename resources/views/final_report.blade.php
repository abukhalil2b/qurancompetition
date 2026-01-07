<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>المسابقة القرآنية فاستمسك - {{ $competition->stage->title }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-100 text-slate-700 font-sans print:bg-white">

    @php
        $judgeTotals = [];
        $judgeNames = [];

        foreach ($reportData as $data) {
            foreach ($data['judge_scores'] as $id => $score) {
                $judgeTotals[$id] = ($judgeTotals[$id] ?? 0) + $score['total'];
                $judgeNames[$id] = $score['judge']->name;
            }
        }

        $div = $reportData->count() * $judgeCount;
        $finalP = $div > 0 ? $grandTotal / $div : 0;

 
    @endphp

    <div
        class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl border border-slate-200 p-10 print:p-0 print:shadow-none print:border-0">

        {{-- Action Bar --}}
        <div class="flex justify-between gap-4 mb-10 print:hidden">
            <button onclick="window.print()"
                class="px-6 py-3 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-900 transition">
                طباعة التقرير
            </button>

            @if ($competition->student_status !== 'finish_competition')
                <a href="{{ route('finish_student', $competition->id) }}"
                    class="px-6 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition">
                    إنهاء المسابقة
                </a>
            @endif
        </div>

        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-2">
                المسابقة القرآنية فاستمسك – {{ $competition->stage->title }}
            </h1>
            <p class="text-sm text-slate-500">{{ $competition->created_at->format('Y/m/d') }}</p>
            <p class="text-slate-400 text-sm">2025 / 1447</p>
        </div>

        {{-- Student Info --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-12">
            <div>
                <p class="text-xs text-slate-400 font-semibold">اسم المتسابق</p>
                <p class="font-bold text-slate-800">{{ $student->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold">المستوى</p>
                <p class="font-bold text-slate-800">{{ $competition->questionset->level }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold">اللجنة</p>
                <p class="font-bold text-slate-800">{{ $competition->committee->title }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold">المركز</p>
                <p class="font-bold text-slate-800">{{ $competition->center->title }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold">عدد المحكمين</p>
                <p class="font-bold text-slate-800">{{ $judgeCount }}</p>
            </div>
        </div>

        {{-- Questions --}}
        @foreach ($reportData as $index => $data)
            <div class="border border-slate-200 rounded-2xl mb-8 overflow-hidden print:break-inside-avoid">

                <div class="flex justify-between items-center bg-slate-50 px-6 py-4 border-b">
                    <span class="font-extrabold text-blue-600">السؤال {{ $index + 1 }}</span>
                    <span class="text-sm text-slate-500">{{ $data['question']->difficulties }}</span>
                </div>

                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-4">{{ $data['question']->content }}</p>

                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500">
                                <th class="text-right px-4 py-3">المحكم</th>
                                <th class="text-right px-4 py-3">الدرجة</th>
                                <th class="text-right px-4 py-3">الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['judge_scores'] as $js)
                                <tr class="border-t">
                                    <td class="px-4 py-3 font-semibold">{{ $js['judge']->name }}</td>
                                    <td class="px-4 py-3">{{ number_format($js['total'], 1) }}</td>
                                    <td class="px-4 py-3 text-slate-400 text-xs">{{ $js['note'] ?? '-' }}</td>
                                </tr>
                            @endforeach

                            <tr class="bg-emerald-50 text-emerald-700 font-bold">
                                <td class="px-4 py-3">المتوسط</td>
                                <td colspan="2" class="px-4 py-3">
                                    {{ number_format($data['average'], 1) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- Final Result --}}
        <div
            class="mt-16 bg-slate-800 text-white rounded-3xl p-10 flex flex-col md:flex-row justify-between items-center gap-8 print:bg-gray-200 print:text-black">

            <div>
                <h2 class="text-2xl font-bold mb-1">النتيجة النهائية</h2>
                <p class="text-sm opacity-70 mb-4">
                    تم احتساب النتيجة بناءً على متوسط تقييمات المحكمين
                </p>

                <div class="flex flex-wrap gap-2">
                    @foreach ($judgeTotals as $id => $total)
                        <span class="text-xs bg-white/10 px-3 py-1 rounded-lg">
                            {{ $judgeNames[$id] }}:
                            {{ number_format($total / $reportData->count(), 1) }}%
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <div class="text-6xl font-black text-amber-400">
                    {{ number_format($finalP, 2) }}%
                </div>
                
            </div>
        </div>

    </div>

</body>

</html>
