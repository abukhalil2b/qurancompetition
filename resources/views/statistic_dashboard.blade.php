@php
    $n = fn ($v) => number_format((int) ($v ?? 0));
    $mins = fn ($v) => $v !== null && $v !== '' ? number_format((float) $v, 1) . ' د' : '—';
    $tones = [
        'rose' => 'bg-rose-500',
        'amber' => 'bg-amber-500',
        'sky' => 'bg-sky-500',
        'teal' => 'bg-indigo-500',
        'emerald' => 'bg-indigo-700',
    ];
    $totalAll = (int) $kpi['total_all'];
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة التحليل الإحصائي للمسابقة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "IBM Plex Sans Arabic", system-ui, sans-serif; background-color: #f8fafc; color: #334155; }
        .tabular { font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
        .avoid-break { break-inside: avoid; page-break-inside: avoid; }
        .data-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(15, 23, 42, 0.04); }
        
        @page { size: A4; margin: 12mm; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #0f172a !important; font-size: 10pt; }
            main { max-width: none !important; padding: 0 !important; }
            .print-header { background: #fff !important; color: #0f172a !important; border-bottom: 2px solid #e2e8f0; padding: 0 0 1rem 0 !important; }
            .data-card { box-shadow: none !important; border: 1px solid #cbd5e1 !important; }
            .center-section { break-before: page; page-break-before: always; }
            .center-section:first-of-type { break-before: auto; page-break-before: auto; }
            .avoid-break { break-inside: avoid; page-break-inside: avoid; }
            .progress { border: 1px solid #cbd5e1; background: transparent !important; }
            .progress > div { background: #64748b !important; }
        }
    </style>
</head>
<body class="antialiased">

<header class="print-header bg-slate-900 text-white">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 sm:px-6 lg:px-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</p>
            <h1 class="mt-1 text-2xl font-bold sm:text-3xl">المؤشرات العامة للمسابقة</h1>
            <p class="mt-2 text-sm text-slate-400 flex items-center gap-2">
                <span>تحديث البيانات: {{ now()->locale('ar')->translatedFormat('j F Y، h:i A') }}</span>
            </p>
        </div>
        <div class="no-print flex flex-wrap gap-2">
            <button type="button" onclick="window.print()" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow hover:bg-slate-100 transition-colors">طباعة التقرير</button>
            <button type="button" onclick="window.location.reload()" class="rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition-colors">تحديث</button>
        </div>
    </div>
</header>

<main class="mx-auto max-w-7xl space-y-8 px-4 py-6 sm:px-6 lg:px-8 sm:py-8">
    @if ($kpi['dq_finished_no_score'] > 0 || $kpi['dq_score_not_finished'] > 0)
        <section class="data-card avoid-break bg-amber-50/50 border-amber-200 p-4 border-l-4 border-l-amber-500">
            <h2 class="font-semibold text-amber-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                بيانات غير متطابقة (تتطلب معالجة)
            </h2>
            <ul class="mt-3 list-inside list-disc space-y-1 text-sm text-amber-800">
                @if ($kpi['dq_finished_no_score'] > 0)
                    <li>{{ $n($kpi['dq_finished_no_score']) }} حالة إنهاء للمسابقة دون تسجيل درجة نهائية في النظام.</li>
                @endif
                @if ($kpi['dq_score_not_finished'] > 0)
                    <li>{{ $n($kpi['dq_score_not_finished']) }} حالة مسجل لها درجة ولم تُحدث حالتها إلى "منتهية".</li>
                @endif
            </ul>
        </section>
    @endif

    {{-- الإجمالي العام (Data Overview) --}}
    <section class="avoid-break">
        <div class="mb-4 flex items-baseline justify-between">
            <h2 class="text-lg font-bold text-slate-800">نظرة عامة على البيانات (Dataset Overview)</h2>
        </div>
        
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-4">
            <article class="data-card p-5">
                <p class="text-sm font-medium text-slate-500">إجمالي المسجلين (N)</p>
                <p class="tabular mt-2 text-3xl font-bold text-slate-900">{{ $n($totalAll) }}</p>
                <p class="mt-2 text-xs text-slate-400">يشمل الحالات المنسحبة</p>
            </article>
            
            <article class="data-card p-5">
                <p class="text-sm font-medium text-slate-500">العينة الفعالة (Active)</p>
                <p class="tabular mt-2 text-3xl font-bold text-slate-900">{{ $n($kpi['total']) }}</p>
                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                    <span>ذكور: <span class="font-semibold tabular text-slate-700">{{ $n($kpi['male']) }}</span></span>
                    <span>إناث: <span class="font-semibold tabular text-slate-700">{{ $n($kpi['female']) }}</span></span>
                </div>
            </article>

            <article class="data-card p-5 border-l-4 border-l-indigo-500 bg-indigo-50/30">
                <p class="text-sm font-medium text-indigo-800">مجموع اللذين أنهوا المسابقة</p>
                <p class="tabular mt-2 text-3xl font-bold text-indigo-700">{{ $n($kpi['finished_total']) }}</p>
                <div class="mt-2 flex items-center justify-between text-xs text-indigo-700/80">
                    <span>ذكور: <span class="font-semibold tabular">{{ $n($kpi['finished_male']) }}</span></span>
                    <span>إناث: <span class="font-semibold tabular">{{ $n($kpi['finished_female']) }}</span></span>
                </div>
            </article>

            <article class="data-card p-5">
                <p class="text-sm font-medium text-slate-500">معدل الإنجاز (Completion Rate)</p>
                <p class="tabular mt-2 text-3xl font-bold text-slate-900">{{ $kpi['completion_rate'] }}%</p>
                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                    <span>الانسحاب: <span class="tabular">{{ $n($kpi['withdraw']) }} ({{ $kpi['withdraw_rate'] }}%)</span></span>
                </div>
            </article>
        </div>

        {{-- تحليل المدة الزمنية --}}
        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="data-card p-5 lg:col-span-2">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    المدة الزمنية من تسجيل الحضور عند اللجنة إلى الانتهاء
                </h3>
                <p class="mt-1 text-xs text-slate-500 mb-4">يعكس متوسط الوقت الفعلي الذي يقضيه المتسابق أمام اللجنة.</p>
                
                <div class="grid grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">المتوسط (Mean)</p>
                        <p class="tabular mt-1 text-2xl font-bold text-slate-800">{{ $mins($kpi['avg_test_minutes']) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">الحد الأدنى (Min)</p>
                        <p class="tabular mt-1 text-2xl font-bold text-slate-800">{{ $mins($kpi['min_test_minutes']) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">الحد الأعلى (Max)</p>
                        <p class="tabular mt-1 text-2xl font-bold text-slate-800">{{ $mins($kpi['max_test_minutes']) }}</p>
                    </div>
                </div>
            </div>

            <div class="data-card p-5">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    زمن الانتظار العام
                </h3>
                <p class="mt-1 text-xs text-slate-500">من التسجيل المبدئي للوصول وحتى الدخول الفعلي للجنة.</p>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">متوسط الانتظار (Wait Mean)</p>
                    <p class="tabular mt-1 text-3xl font-bold text-slate-800">{{ $mins($kpi['avg_wait_minutes']) }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- التوزيع الإحصائي حسب المستويات --}}
    <section>
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">التوزيع الإحصائي للدرجات (Score Distribution)</h2>
        </div>
        <div class="grid gap-5 xl:grid-cols-2">
            @foreach ($levelStats as $lv)
                @php $sc = $lv['scores']; @endphp
                <article class="data-card avoid-break p-5 sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">{{ $lv['title'] }}</h3>
                            <p class="mt-1 text-sm text-slate-500">حجم العينة المُقيّمة: {{ $n($sc['count']) }} (العظمى: {{ $lv['max'] }})</p>
                        </div>
                        <div class="text-left bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">نسبة الإنجاز</p>
                            <p class="tabular text-xl font-bold text-slate-800">{{ $lv['finished_width'] }}%</p>
                        </div>
                    </div>
                    
                    @if ($sc['count'] > 0)
                        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ([
                                ['المتوسط (μ)', $sc['avg']], 
                                ['الوسيط (M)', $sc['median']], 
                                ['الانحراف (σ)', $sc['std']], 
                                ['أعلى درجة (Max)', $sc['max']]
                            ] as [$label, $value])
                                <div>
                                    <p class="text-xs text-slate-500">{{ $label }}</p>
                                    <p class="tabular mt-1 text-lg font-semibold text-slate-900">{{ $value ?? '—' }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 flex items-center gap-4 text-sm text-slate-600 bg-slate-50 px-3 py-2 rounded-md">
                            <span>متوسط الذكور: <span class="font-bold tabular text-slate-800">{{ $lv['avg_male'] ?? '—' }}</span></span>
                            <span class="text-slate-300">|</span>
                            <span>متوسط الإناث: <span class="font-bold tabular text-slate-800">{{ $lv['avg_female'] ?? '—' }}</span></span>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500 italic">لا توجد داتا مسجلة (No data points available).</p>
                    @endif

                    <h4 class="mt-6 mb-3 text-sm font-semibold text-slate-700">التكرارات الإحصائية (Frequency)</h4>
                    <div class="space-y-3">
                        @foreach ($lv['bins'] as $bin)
                            <div class="grid grid-cols-[160px_1fr_60px] items-center gap-3 text-sm">
                                <span class="text-slate-600 truncate text-xs" title="{{ $bin['label'] }}">{{ $bin['label'] }}</span>
                                <div class="progress h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $tones[$bin['tone']] }} transition-all" style="width: {{ $bin['pct'] }}%"></div>
                                </div>
                                <span class="tabular text-left text-xs font-medium text-slate-700">{{ $n($bin['count']) }} <span class="text-slate-400">({{ $bin['pct'] }}%)</span></span>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- التقسيم الجغرافي / المراكز --}}
    <section>
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-800">التحليل حسب المراكز (Centers Breakdown)</h2>
        </div>

        @forelse ($centerSections as $center)
            <article class="center-section data-card mb-6 p-5 sm:p-6">
                <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-slate-100 pb-5">
    <div>
        <h3 class="text-xl font-bold text-slate-900">{{ $center['title'] }}</h3>
        <p class="mt-1 text-sm text-slate-500">
            الإنجاز: <span class="font-bold">{{ $center['completion_rate'] }}%</span> 
            (أنهى {{ $n($center['finished']) }} من {{ $n($center['total']) }} فعليين)
            <span class="text-slate-300 mx-1">|</span>
            الإجمالي الشامل بالمنسحبين: <span class="font-semibold tabular text-slate-700">{{ $n($center['total_all']) }}</span>
        </p>
    </div>
    <div class="flex gap-4 text-center">
        <div><p class="text-xs text-slate-500 uppercase">الحضور</p><p class="tabular mt-1 text-xl font-bold text-slate-800">{{ $n($center['attended']) }}</p></div>
        <div class="border-r border-slate-200 pr-4"><p class="text-xs text-slate-500 uppercase">المنسحبون</p><p class="tabular mt-1 text-xl font-bold text-slate-800">{{ $n($center['withdraw']) }}</p></div>
    </div>
</header>

                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ($center['levels'] as $lv)
                        @php $sc = $lv['scores']; @endphp
                        <section class="avoid-break rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div>
                                    <h4 class="font-semibold text-slate-800">{{ $lv['title'] }}</h4>
                                    <p class="mt-1 text-xs text-slate-500">الذكور: {{ $n($lv['male']) }} | الإناث: {{ $n($lv['female']) }}</p>
                                </div>
                                <div class="text-left">
                                    <p class="tabular text-lg font-bold text-slate-900">{{ $lv['finished_width'] }}%</p>
                                    <p class="text-xs text-slate-500">نسبة الإنجاز</p>
                                </div>
                            </div>

                            @if ($sc['count'] > 0)
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-white border border-slate-100 rounded p-2"><p class="text-[10px] text-slate-500 uppercase">المتوسط</p><p class="tabular font-bold text-slate-800">{{ $sc['avg'] }}</p></div>
                                    <div class="bg-white border border-slate-100 rounded p-2"><p class="text-[10px] text-slate-500 uppercase">متوسط النسبة</p><p class="tabular font-bold text-slate-800">{{ $sc['avg_pct'] }}%</p></div>
                                </div>
                            @endif

                            <div class="space-y-2">
                                @foreach ($lv['bins'] as $bin)
                                    <div class="grid grid-cols-[80px_1fr_40px] items-center gap-2 text-xs">
                                        <span class="text-slate-500 truncate" title="{{ $bin['label'] }}">{{ $bin['label'] }}</span>
                                        <div class="progress h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                                            <div class="h-full rounded-full {{ $tones[$bin['tone']] }}" style="width: {{ $bin['pct'] }}%"></div>
                                        </div>
                                        <span class="tabular text-left text-slate-700">{{ $n($bin['count']) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </article>
        @empty
            <div class="data-card p-8 text-center text-slate-500 text-sm">لا توجد بيانات مكانية لعرضها. (No spatial data)</div>
        @endforelse
    </section>

    {{-- سلسلة زمنية (Time Series) --}}
    <section class="data-card avoid-break">
        <header class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-lg font-bold text-slate-800">السلسلة الزمنية للإنجاز (Time Series)</h2>
            <p class="mt-1 text-xs text-slate-500">حجم العينة التي تنهي الاختبار بمرور الوقت.</p>
        </header>
        <div class="p-5 space-y-4">
            @forelse ($finishedByDate as $day)
                <div class="grid items-center gap-4 md:grid-cols-[100px_minmax(0,1fr)_200px]">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $day['day_ar'] }}</p>
                        <p class="tabular text-[11px] text-slate-400">{{ $day['date'] }}</p>
                    </div>
                    <div class="progress h-3 w-full overflow-hidden rounded-md bg-slate-100">
                        <div class="h-full rounded-md bg-indigo-500 transition-all" style="width: {{ $day['width'] }}%"></div>
                    </div>
                    <div class="text-sm md:text-left text-slate-600">
                        <b class="tabular text-slate-900">{{ $n($day['total']) }}</b> {{ $day['label'] }} 
                        <span class="text-xs text-slate-400">({{ $day['pct'] }}%)</span>
                        <div class="mt-1 text-[11px] text-slate-400 flex gap-2 justify-start md:justify-end">
                            <span>ذ: {{ $n($day['males']) }}</span>
                            <span>إ: {{ $n($day['females']) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500 text-center py-4">لم يتم تسجيل أي إنهاء حتى الآن.</p>
            @endforelse
        </div>
    </section>
</main>
<footer class="pb-8 text-center text-xs text-slate-400 font-mono">Generated by Assessment Analytics Engine</footer>
</body>
</html>