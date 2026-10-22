@php
    $n = fn ($v) => number_format((int) $v);
    $pct = fn ($a, $b) => $b > 0 ? round($a / $b * 100, 1) : 0;
    $mins = fn ($v) => $v ? $v . ' د' : '—';

    $tones = [
        'rose'    => 'bg-rose-400',
        'amber'   => 'bg-amber-400',
        'sky'     => 'bg-sky-400',
        'teal'    => 'bg-teal-500',
        'emerald' => 'bg-emerald-600',
    ];

    $statusLabels = [
        'withdraw'           => 'منسحب',
        'finish_competition' => 'أنهى المسابقة',
        'with_committee'     => 'مع اللجنة',
        'waiting'            => 'في الانتظار',
        'registered'         => 'مسجّل',
    ];

    $totalAll = $kpi['total_all'];
    $scopeTitle = $selectedCenter ? $selectedCenter->title : 'جميع المراكز';
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة الإحصائيات — مساري</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- إن كان مشروعك يبني Tailwind عبر Vite احذف السطرين التاليين --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['"IBM Plex Sans Arabic"', 'system-ui', 'sans-serif'] } } } }
    </script>
    <style>
        .tabular { font-variant-numeric: tabular-nums; }
        @media print { .no-print { display: none !important; } body { background: #fff; } }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">

{{-- ===================== الترويسة ===================== --}}
<header class="bg-[#062426] text-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm text-teal-200/80">مساري</p>
                <h1 class="mt-1 text-2xl font-bold sm:text-3xl">لوحة إحصائيات المسابقة</h1>
                <p class="mt-2 text-sm text-teal-100/80">النطاق: {{ $scopeTitle }} — آخر تحديث {{ now()->locale('ar')->translatedFormat('j F Y، h:i A') }}</p>
            </div>

            <form method="GET" action="{{ route('statistic_dashboard') }}" class="no-print flex items-center gap-2">
                <label for="center_id" class="text-sm text-teal-100/80">المركز</label>
                <select id="center_id" name="center_id" onchange="this.form.submit()"
                        class="min-w-[14rem] rounded-xl border-0 bg-white/10 px-4 py-2.5 text-sm text-white ring-1 ring-white/20 focus:outline-none focus:ring-2 focus:ring-teal-300">
                    <option value="" class="text-slate-800">جميع المراكز</option>
                    @foreach ($centers as $c)
                        <option value="{{ $c->id }}" class="text-slate-800" @selected($selectedCenter && $selectedCenter->id === $c->id)>{{ $c->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</header>

<main class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">

    {{-- ===================== تنبيهات جودة البيانات ===================== --}}
    @if ($kpi['dq_finished_no_score'] > 0 || $kpi['dq_score_not_finished'] > 0)
        <section class="rounded-2xl bg-amber-50 p-4 ring-1 ring-amber-200">
            <h2 class="text-sm font-semibold text-amber-900">تحتاج بعض السجلات إلى مراجعة</h2>
            <ul class="mt-2 space-y-1 text-sm text-amber-800">
                @if ($kpi['dq_finished_no_score'] > 0)
                    <li>{{ $n($kpi['dq_finished_no_score']) }} متسابق أنهى المسابقة دون درجة نهائية.</li>
                @endif
                @if ($kpi['dq_score_not_finished'] > 0)
                    <li>{{ $n($kpi['dq_score_not_finished']) }} متسابق لديه درجة نهائية ولم تُسجَّل حالته منتهية.</li>
                @endif
            </ul>
        </section>
    @endif

    {{-- ===================== المؤشرات الرئيسية ===================== --}}
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- المتسابقون --}}
        <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
            <p class="text-sm text-slate-500">المتسابقون المسجّلون</p>
            <p class="tabular mt-2 text-4xl font-bold text-[#062426]">{{ $n($kpi['total']) }}</p>
            <div class="mt-4 flex h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="bg-[#0d4b4e]" style="width: {{ $pct($kpi['male'], $kpi['total']) }}%"></div>
                <div class="bg-teal-300" style="width: {{ $pct($kpi['female'], $kpi['total']) }}%"></div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><i class="size-2 rounded-full bg-[#0d4b4e]"></i>ذكور {{ $n($kpi['male']) }}</span>
                <span class="flex items-center gap-1.5"><i class="size-2 rounded-full bg-teal-300"></i>إناث {{ $n($kpi['female']) }}</span>
            </div>
        </div>



        {{-- أنهوا --}}
        <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
            <p class="text-sm text-slate-500">أنهوا المسابقة</p>
            <p class="tabular mt-2 text-4xl font-bold text-[#062426]">{{ $n($kpi['finished_total']) }}
                <span class="text-base font-medium text-slate-400">({{ $kpi['completion_rate'] }}%)</span></p>
            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-600" style="width: {{ $kpi['completion_rate'] }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">اليوم: {{ $n($kpi['today_total']) }} (ذكور {{ $n($kpi['today_male']) }} / إناث {{ $n($kpi['today_female']) }})</p>
        </div>

        {{-- الانسحاب --}}
        <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-100">
            <p class="text-sm text-slate-500">المنسحبون</p>
            <p class="tabular mt-2 text-4xl font-bold text-[#062426]">{{ $n($kpi['withdraw']) }}
                <span class="text-base font-medium text-slate-400">({{ $kpi['withdraw_rate'] }}%)</span></p>
            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-rose-400" style="width: {{ $kpi['withdraw_rate'] }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">ذكور {{ $n($kpi['withdraw_male']) }} / إناث {{ $n($kpi['withdraw_female']) }} — من أصل {{ $n($totalAll) }} سجل</p>
        </div>
    </section>

    {{-- ===================== مسار المتسابق + الأزمنة + الحالات ===================== --}}
    <section class="grid gap-4 lg:grid-cols-3">
        {{-- الأزمنة --}}
        <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-100">
            <h2 class="text-base font-semibold text-[#062426]">الأزمنة</h2>
            <dl class="mt-5 space-y-5">
                <div>
                    <dt class="text-sm text-slate-500">متوسط مدة الاختبار مع اللجنة</dt>
                    <dd class="tabular mt-1 text-3xl font-bold text-[#062426]">{{ $mins($kpi['avg_test_minutes']) }}</dd>
                    <dd class="mt-1 text-xs text-slate-500">أقل مدة {{ $mins($kpi['min_test_minutes']) }} · أطول مدة {{ $mins($kpi['max_test_minutes']) }}</dd>
                </div>
                <div class="border-t border-slate-100 pt-5">
                    <dt class="text-sm text-slate-500">متوسط الانتظار قبل الدخول للجنة</dt>
                    <dd class="tabular mt-1 text-3xl font-bold text-[#062426]">{{ $mins($kpi['avg_wait_minutes']) }}</dd>
                    <dd class="mt-1 text-xs text-slate-500">من تسجيل الحضور حتى الدخول للجنة</dd>
                </div>
            </dl>
        </div>
    </section>



    {{-- ===================== المستويات ===================== --}}
    <section>
        <h2 class="mb-4 text-lg font-semibold text-[#062426]">المستويات والدرجات</h2>
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($levelStats as $lv)
                @php $sc = $lv['scores']; @endphp
                <article class="rounded-2xl bg-white p-6 ring-1 ring-slate-100">
                    <header class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-[#062426]">{{ $lv['title'] }}</h3>
                            <p class="mt-0.5 text-xs text-slate-500">الدرجة العظمى {{ $lv['max'] }} · {{ $lv['share'] }}% من المتسابقين</p>
                        </div>
                        <div class="text-end">
                            <p class="tabular text-3xl font-bold text-[#0d4b4e]">{{ $n($lv['total']) }}</p>
                            <p class="text-xs text-slate-500">ذكور {{ $n($lv['male']) }} · إناث {{ $n($lv['female']) }}</p>
                        </div>
                    </header>

                    <div class="mt-5">
                        <div class="mb-1.5 flex justify-between text-xs text-slate-500">
                            <span>أنهى {{ $n($lv['finished']) }} من {{ $n($lv['total']) }} (حضر {{ $n($lv['attended']) }})</span>
                            <span class="tabular">{{ $lv['finished_width'] }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-600" style="width: {{ $lv['finished_width'] }}%"></div>
                        </div>
                    </div>

                    @if ($sc['count'] > 0)
                        <dl class="mt-6 grid grid-cols-3 gap-3 text-center">
                            @foreach ([['المتوسط', $sc['avg']], ['الوسيط', $sc['median']], ['الانحراف المعياري', $sc['std']]] as [$l, $v])
                                <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-100">
                                    <dt class="text-xs text-slate-500">{{ $l }}</dt>
                                    <dd class="tabular mt-1 text-lg font-bold text-[#062426]">{{ $v ?? '—' }}</dd>
                                </div>
                            @endforeach
                            @foreach ([['أعلى درجة', $sc['max']], ['أدنى درجة', $sc['min']], ['المتوسط %', $sc['avg_pct'] !== null ? $sc['avg_pct'] . '%' : null]] as [$l, $v])
                                <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-100">
                                    <dt class="text-xs text-slate-500">{{ $l }}</dt>
                                    <dd class="tabular mt-1 text-lg font-bold text-[#062426]">{{ $v ?? '—' }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <p class="mt-4 text-xs text-slate-500">
                            متوسط الذكور <b class="tabular text-slate-700">{{ $lv['avg_male'] ?? '—' }}</b>
                            · متوسط الإناث <b class="tabular text-slate-700">{{ $lv['avg_female'] ?? '—' }}</b>
                            · عدد الدرجات {{ $n($sc['count']) }}
                        </p>

                        <h4 class="mt-6 text-sm font-semibold text-slate-700">توزيع الدرجات</h4>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($lv['bins'] as $b)
                                <li class="grid grid-cols-[6.5rem_1fr_5.5rem] items-center gap-3 text-xs">
                                    <span class="text-slate-600">{{ $b['label'] }}</span>
                                    <span class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                        <span class="block h-full rounded-full {{ $tones[$b['tone']] }}" style="width: {{ $b['width'] }}%"></span>
                                    </span>
                                    <span class="tabular text-end text-slate-500"><b class="text-slate-700">{{ $n($b['count']) }}</b> ({{ $b['pct'] }}%)</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-500 ring-1 ring-slate-100">لا توجد درجات نهائية مسجلة لهذا المستوى بعد.</p>
                    @endif
                </article>
            @endforeach
        </div>
    </section>

    {{-- ===================== التفصيل: المراكز أو اللجان ===================== --}}
    <section class="rounded-2xl bg-white ring-1 ring-slate-100">
        <header class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-base font-semibold text-[#062426]">{{ $selectedCenter ? 'أداء اللجان' : 'أداء المراكز' }}</h2>
            <p class="mt-0.5 text-sm text-slate-500">متوسط الدرجات لكل مستوى، ونسبة الإنهاء من المسجّلين.</p>
        </header>

        @if ($groupRows->isEmpty())
            <p class="p-6 text-sm text-slate-500">لا توجد بيانات لعرضها.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-start font-medium">{{ $selectedCenter ? 'اللجنة' : 'المركز' }}</th>
                            <th class="px-4 py-3 text-center font-medium">المسجّلون</th>
                            <th class="px-4 py-3 text-center font-medium">الحضور</th>
                            <th class="px-4 py-3 text-center font-medium">الانسحاب</th>
                            <th class="px-4 py-3 text-start font-medium">الإنهاء</th>
                            <th class="px-4 py-3 text-center font-medium">م1 (من 120)</th>
                            <th class="px-4 py-3 text-center font-medium">م2 (من 100)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($groupRows as $r)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-3.5">
                                    <p class="font-medium text-slate-800">{{ $r['label'] }}</p>
                                    @if ($r['sub'])<p class="text-xs text-slate-400">{{ $r['sub'] }}</p>@endif
                                </td>
                                <td class="tabular px-4 py-3.5 text-center">{{ $n($r['total']) }}</td>
                                <td class="tabular px-4 py-3.5 text-center">{{ $n($r['attended']) }}</td>
                                <td class="tabular px-4 py-3.5 text-center {{ $r['withdraw'] ? 'text-rose-600' : 'text-slate-400' }}">{{ $n($r['withdraw']) }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-24 overflow-hidden rounded-full bg-slate-100">
                                            <span class="block h-full rounded-full bg-emerald-600" style="width: {{ $r['bar'] }}%"></span>
                                        </span>
                                        <span class="tabular text-xs text-slate-600">{{ $n($r['finished']) }} ({{ $r['bar'] }}%)</span>
                                    </div>
                                </td>
                                @foreach (['l1', 'l2'] as $k)
                                    <td class="tabular px-4 py-3.5 text-center">
                                        @if ($r[$k]['count'] > 0)
                                            <b class="text-[#062426]">{{ $r[$k]['avg'] }}</b>
                                            <span class="block text-xs text-slate-400">{{ $r[$k]['avg_pct'] }}% · {{ $r[$k]['count'] }} درجة</span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ===================== الإنهاء حسب الأيام ===================== --}}
    <section class="rounded-2xl bg-white ring-1 ring-slate-100">
        <header class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-base font-semibold text-[#062426]">الإنهاء حسب الأيام</h2>
            <p class="mt-0.5 text-sm text-slate-500">آخر 30 يومًا فيها إنهاء، من الأحدث إلى الأقدم.</p>
        </header>

        @forelse ($finishedByDate as $d)
            <div class="grid items-center gap-x-4 gap-y-2 border-b border-slate-100 px-6 py-3.5 last:border-0 md:grid-cols-[9rem_1fr_16rem]">
                <div>
                    <p class="font-medium text-slate-800">{{ $d['day_ar'] }}</p>
                    <p class="tabular text-xs text-slate-400">{{ $d['date'] }}</p>
                </div>
                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-[#0d4b4e]" style="width: {{ $d['width'] }}%"></div>
                </div>
                <div class="tabular text-sm text-slate-600 md:text-end">
                    <b class="text-[#062426]">{{ $n($d['total']) }}</b> {{ $d['label'] }}
                    <span class="text-xs text-slate-400">({{ $d['pct'] }}%)</span>
                    <p class="text-xs text-slate-400">ذكور {{ $d['males'] }} · إناث {{ $d['females'] }} · م1 {{ $d['level_1'] }} · م2 {{ $d['level_2'] }}</p>
                </div>
            </div>
        @empty
            <p class="p-6 text-sm text-slate-500">لم يُسجَّل إنهاء لأي متسابق حتى الآن.</p>
        @endforelse
    </section>

</main>

<footer class="pb-10 text-center text-xs text-slate-400">مساري — لوحة الإحصائيات</footer>
</body>
</html>