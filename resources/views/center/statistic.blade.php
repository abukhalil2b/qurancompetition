<!DOCTYPE html>
<html lang="en-GB" dir="rtl">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <title>{{ $title ?? 'التقرير الإحصائي الشامل - ' . $center->title }}</title>

    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <link rel="icon" type="image/svg" href="{{ asset('favicon.svg') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- إعدادات الطباعة والتصدير لتغطية كامل عرض الصفحة وتجنب الفراغات -->
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #ffffff !important;
            }

            .no-print {
                display: none !important;
            }

            .print-break-inside-avoid {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .wide-print-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 Helvetica text-slate-800" dir="rtl">
    @php
        $k = fn(string $key) => $kpi[$key] ?? 0;
        $fmt = fn($v, $d = 2) => $v === null ? '—' : rtrim(rtrim(number_format((float) $v, $d), '0'), '.');

        $statusLabels = [
            'registration' => ['label' => __('registration'), 'badge' => 'bg-blue-50 text-blue-700 ring-blue-200'],
            'present' => ['label' => __('present'), 'badge' => 'bg-amber-50 text-amber-700 ring-amber-200'],
            'with_committee' => ['label' => __('with_committee'), 'badge' => 'bg-rose-50 text-rose-700 ring-rose-200'],
            'waiting_finalization' => [
                'label' => __('waiting_finalization'),
                'badge' => 'bg-violet-50 text-violet-700 ring-violet-200',
            ],
            'finish_competition' => [
                'label' => __('finish_competition'),
                'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            ],
            'withdraw' => ['label' => __('withdraw'), 'badge' => 'bg-slate-100 text-slate-600 ring-slate-300'],
        ];

        $isAdmin = auth()->user()?->user_type === 'admin';
        $activeCommitteesCount = $center->committees->where('active', true)->count();
    @endphp

    <div class="min-h-screen flex flex-col justify-between">
        <main class="w-full">

            {{-- ================= شريط التحكم العلوي (يختفي عند الطباعة) ================= --}}
            <div
                class="bg-gradient-to-l from-[#041d1f] via-[#062426] to-[#0b383b] text-white no-print shadow-lg border-b border-white/10">
                <div
                    class="w-full px-6 lg:px-12 py-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-emerald-300 font-semibold mb-1">
                            <a href="{{ route('center.index') }}" class="hover:underline">المراكز</a>
                            <span>/</span>
                            <a href="{{ route('center.show', $center->id) }}"
                                class="hover:underline">{{ $center->title }}</a>
                            <span>/</span>
                            <span>التقرير الإحصائي الشامل</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                            مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — {{ $center->title }}
                        </h1>
                        <p class="text-xs text-white/70 mt-1">
                            لوحة تحليل بيانات واسعة (Wide View) — جاهزة للطباعة والتصدير الفوري كملف PDF
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('center.show', $center->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold ring-1 ring-white/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            قائمة المتسابقين
                        </a>

                        <button type="button" onclick="window.print()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#041819] text-xs font-extrabold shadow-md transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            طباعة / حفظ PDF
                        </button>
                    </div>
                </div>
            </div>

            {{-- ================= الترويسة الرسمية للطباعة فقط ================= --}}
            <div class="hidden print:block border-b-2 border-slate-900 pb-4 mb-6 px-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-500">مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</p>
                        <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $center->title }}</h1>
                        <p class="text-xs text-slate-600 mt-1">
                            إجمالي اللجان: {{ $center->committees->count() }} (النشطة: {{ $activeCommitteesCount }})
                        </p>
                    </div>
                    <div class="text-left text-xs text-slate-600 space-y-1">
                        <p><span class="font-bold text-slate-900">تاريخ التقرير:</span> <span
                                dir="ltr">{{ now()->format('Y/m/d H:i') }}</span></p>
                        <p><span class="font-bold text-slate-900">إجمالي المسجلين:</span>
                            {{ number_format($k('total_all')) }} متسابق</p>
                        <p><span class="font-bold text-slate-900">نسبة الإنجاز العام:</span>
                            {{ $k('completion_rate') }}%</p>
                    </div>
                </div>
            </div>

            {{-- ================= الحاوية الواسعة للتقرير (Wide Container) ================= --}}
            <div class="w-full px-4 lg:px-10 py-8 wide-print-container space-y-8">

                {{-- تنبيهات تدقيق البيانات للمشرفين --}}
                @if ($isAdmin && $k('dq_finished_no_score') + $k('dq_score_not_finished') > 0)
                    <div
                        class="rounded-2xl bg-amber-50 ring-1 ring-amber-300 p-4 text-xs text-amber-900 flex items-start gap-3 print-break-inside-avoid">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="space-y-1">
                            <p class="font-extrabold text-sm">ملاحظات تدقيق جودة البيانات</p>
                            @if ($k('dq_finished_no_score'))
                                <p>• يوجد <strong>{{ $k('dq_finished_no_score') }}</strong> متسابق حالته «أنهى
                                    المسابقة» ولكن لم تُرصد له درجة نهائية.</p>
                            @endif
                            @if ($k('dq_score_not_finished'))
                                <p>• يوجد <strong>{{ $k('dq_score_not_finished') }}</strong> متسابق رُصدت له درجة
                                    نهائية ولكن حالته ليست «أنهى المسابقة».</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ================= 1. الملخص التنفيذي ومؤشرات الأداء (KPIs) ================= --}}
                <section class="space-y-4 print-break-inside-avoid">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-5 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                            <h2 class="text-base md:text-lg font-extrabold text-slate-900">أولاً: الملخص التنفيذي
                                ومؤشرات الأداء (KPIs)</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">الإحصائيات للمتسابقين غير المنسحبين ما لم
                            يُذكر خلاف ذلك</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                        {{-- إجمالي المسجلين --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-500">إجمالي المسجلين</span>
                                <p class="text-2xl font-extrabold text-slate-900 mt-1">
                                    {{ number_format($k('total_all')) }}</p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                                <span>شامل المنسحبين</span>
                                <span class="font-bold text-slate-700">100%</span>
                            </div>
                        </div>

                        {{-- المستمرون --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-teal-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-teal-800">المتسابقون المستمرون</span>
                                <p class="text-2xl font-extrabold text-[#0d4b4e] mt-1">{{ number_format($k('total')) }}
                                </p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] flex items-center justify-between">
                                <span class="text-blue-700 font-bold">ذكور: {{ number_format($k('male')) }}</span>
                                <span class="text-rose-700 font-bold">إناث: {{ number_format($k('female')) }}</span>
                            </div>
                        </div>

                        {{-- الحضور --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">سجلوا الحضور</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 text-[10px] font-extrabold">{{ $k('attendance_rate') }}%</span>
                                </div>
                                <p class="text-2xl font-extrabold text-sky-600 mt-1">
                                    {{ number_format($k('attended')) }}</p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] flex items-center justify-between text-slate-500">
                                <span>ذكور: {{ number_format($k('attended_male')) }}</span>
                                <span>إناث: {{ number_format($k('attended_female')) }}</span>
                            </div>
                        </div>

                        {{-- أنهوا المسابقة --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-emerald-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-emerald-800">أنهوا المسابقة</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-extrabold">{{ $k('completion_rate') }}%</span>
                                </div>
                                <p class="text-2xl font-extrabold text-emerald-600 mt-1">
                                    {{ number_format($k('finished_total')) }}</p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] flex items-center justify-between">
                                <span class="text-blue-700 font-bold">ذكور:
                                    {{ number_format($k('finished_male')) }}</span>
                                <span class="text-rose-700 font-bold">إناث:
                                    {{ number_format($k('finished_female')) }}</span>
                            </div>
                        </div>

                        {{-- أنهوا اليوم --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-500">أنهوا المسابقة اليوم</span>
                                <p class="text-2xl font-extrabold text-indigo-600 mt-1">
                                    {{ number_format($k('today_total')) }}</p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] flex items-center justify-between text-slate-600">
                                <span>ذكور: {{ number_format($k('today_male')) }}</span>
                                <span>إناث: {{ number_format($k('today_female')) }}</span>
                            </div>
                        </div>

                        {{-- المنسحبون --}}
                        <div
                            class="bg-white rounded-2xl p-4 ring-1 ring-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">المنسحبون</span>
                                    <span
                                        class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-extrabold">{{ $k('withdraw_rate') }}%</span>
                                </div>
                                <p class="text-2xl font-extrabold text-amber-600 mt-1">
                                    {{ number_format($k('withdraw')) }}</p>
                            </div>
                            <div
                                class="mt-3 pt-2 border-t border-slate-100 text-[11px] flex items-center justify-between text-slate-500">
                                <span>ذكور: {{ number_format($k('withdraw_male')) }}</span>
                                <span>إناث: {{ number_format($k('withdraw_female')) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- توزيع المراحل وزمن التقييم --}}
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                        <div class="xl:col-span-2 bg-white rounded-2xl p-5 ring-1 ring-slate-200 shadow-sm">
                            <h3 class="text-xs font-extrabold text-slate-700 mb-3">توزيع المتسابقين حسب المراحل الحالية
                                (جميع المسجلين)</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($statusLabels as $statusKey => $meta)
                                    @php
                                        $cnt = $statusBreakdown[$statusKey] ?? 0;
                                        $pct = $k('total_all') > 0 ? round(($cnt / $k('total_all')) * 100, 1) : 0;
                                    @endphp
                                    <div
                                        class="rounded-xl px-3.5 py-3 ring-1 {{ $meta['badge'] }} flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold">{{ $meta['label'] }}</p>
                                            <p class="text-[10px] opacity-75">{{ $pct }}% من إجمالي المركز
                                            </p>
                                        </div>
                                        <span class="text-lg font-extrabold">{{ number_format($cnt) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="bg-white rounded-2xl p-5 ring-1 ring-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="text-xs font-extrabold text-slate-700">مؤشرات الأوقات والتقييم</h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">زمن الاختبار الفعلي ومتوسط فترة الانتظار
                                </p>
                            </div>

                            {{-- بطاقات مدة الاختبار الفعلي مع اللجنة --}}
                            <div class="my-3">
                                <span class="text-[11px] font-bold text-slate-600 block mb-1.5">مدة الاختبار الفعلي (مع
                                    اللجنة):</span>
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div class="rounded-xl bg-slate-50 p-2.5 ring-1 ring-slate-200">
                                        <span class="text-[10px] font-bold text-slate-500 block">المتوسط</span>
                                        <span
                                            class="text-lg font-extrabold text-[#0d4b4e]">{{ $k('avg_test_minutes') ?: '—' }}</span>
                                        <span class="text-[10px] text-slate-400 block">دقيقة</span>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-2.5 ring-1 ring-slate-200">
                                        <span class="text-[10px] font-bold text-slate-500 block">أقل مدة</span>
                                        <span
                                            class="text-lg font-extrabold text-emerald-600">{{ $k('min_test_minutes') ?: '—' }}</span>
                                        <span class="text-[10px] text-slate-400 block">دقيقة</span>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-2.5 ring-1 ring-slate-200">
                                        <span class="text-[10px] font-bold text-slate-500 block">أقصى مدة</span>
                                        <span
                                            class="text-lg font-extrabold text-amber-600">{{ $k('max_test_minutes') ?: '—' }}</span>
                                        <span class="text-[10px] text-slate-400 block">دقيقة</span>
                                    </div>
                                </div>
                            </div>

                            {{-- متوسط وقت الانتظار --}}
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-600">متوسط وقت الانتظار (قبل اللجنة):</span>
                                <span class="font-extrabold text-indigo-700 font-mono text-sm">
                                    {{ $k('avg_wait_minutes') ? $k('avg_wait_minutes') . ' دقيقة' : '—' }}
                                </span>
                            </div>

                            {{-- الطلاب المتبقون --}}
                            <div
                                class="text-[11px] text-slate-500 flex items-center justify-between pt-2 border-t border-slate-100">
                                <span>لم يحضروا بعد (غير منسحبين):</span>
                                <span class="font-extrabold text-slate-800">{{ number_format($k('not_attended')) }}
                                    متسابق</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ================= 2. التحليل التفصيلي للمستويات والدرجات ================= --}}
                <section class="space-y-4 print-break-inside-avoid">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-5 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                            <h2 class="text-base md:text-lg font-extrabold text-slate-900">ثانياً: إحصائيات المستويات
                                وتحليل الدرجات</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">المستوى الأول من 120 درجة · المستوى الثاني
                            من 100 درجة</span>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        @foreach ($levelStats as $l)
                            @php
                                $sc = $l['scores'];
                                $isL1 = $l['level'] === 1;
                                $headerBg = $isL1 ? 'bg-indigo-950 text-white' : 'bg-[#062426] text-white';
                                $accentBar = $isL1 ? 'bg-indigo-600' : 'bg-[#0d4b4e]';
                            @endphp

                            <div
                                class="bg-white rounded-2xl ring-1 ring-slate-200 shadow-sm overflow-hidden print-break-inside-avoid">
                                <div class="{{ $headerBg }} px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <span class="text-[11px] text-white/70 font-semibold">تحليل المستوى
                                            المستقل</span>
                                        <h3 class="text-base font-extrabold">{{ $l['title'] }}</h3>
                                    </div>
                                    <div class="text-left">
                                        <span
                                            class="inline-block px-3 py-1 rounded-lg bg-white/15 text-xs font-extrabold">
                                            الدرجة العظمى: {{ $l['max'] }}
                                        </span>
                                        <p class="text-[11px] text-white/75 mt-1">{{ $l['share'] }}% من متسابقي
                                            المركز</p>
                                    </div>
                                </div>

                                <div class="p-6 space-y-5">
                                    <div class="grid grid-cols-4 gap-3 text-center">
                                        <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-200">
                                            <span
                                                class="text-[11px] text-slate-500 font-semibold block">المستمرون</span>
                                            <span
                                                class="text-lg font-extrabold text-slate-900">{{ number_format($l['total']) }}</span>
                                        </div>
                                        <div class="rounded-xl bg-blue-50/70 p-3 ring-1 ring-blue-200">
                                            <span class="text-[11px] text-blue-700 font-semibold block">ذكور</span>
                                            <span
                                                class="text-lg font-extrabold text-blue-800">{{ number_format($l['male']) }}</span>
                                        </div>
                                        <div class="rounded-xl bg-rose-50/70 p-3 ring-1 ring-rose-200">
                                            <span class="text-[11px] text-rose-700 font-semibold block">إناث</span>
                                            <span
                                                class="text-lg font-extrabold text-rose-800">{{ number_format($l['female']) }}</span>
                                        </div>
                                        <div class="rounded-xl bg-emerald-50/70 p-3 ring-1 ring-emerald-200">
                                            <span class="text-[11px] text-emerald-700 font-semibold block">أنهوا</span>
                                            <span
                                                class="text-lg font-extrabold text-emerald-800">{{ number_format($l['finished']) }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between text-xs mb-1.5">
                                            <span class="font-bold text-slate-600">نسبة إنهاء المسابقة في
                                                {{ $l['title'] }}</span>
                                            <span class="font-extrabold text-emerald-700">
                                                {{ $l['finished_width'] }}% ({{ number_format($l['finished']) }} من
                                                {{ number_format($l['total']) }})
                                            </span>
                                        </div>
                                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-emerald-500"
                                                style="width: {{ $l['finished_width'] }}%"></div>
                                        </div>
                                    </div>

                                    @if ($sc['count'] === 0)
                                        <div
                                            class="rounded-xl bg-slate-50 p-8 text-center text-xs text-slate-400 font-semibold">
                                            لا توجد درجات مرصودة لهذا المستوى حتى الآن
                                        </div>
                                    @else
                                        <div class="space-y-3 pt-2 border-t border-slate-100">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-xs font-extrabold text-slate-800">المؤشرات الإحصائية
                                                    للدرجات</h4>
                                                <span class="text-[11px] font-semibold text-slate-500">عدد الدرجات:
                                                    {{ number_format($sc['count']) }}</span>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                <div class="rounded-xl bg-[#0d4b4e] text-white p-3.5">
                                                    <span class="text-[10px] text-white/75 font-semibold block">أعلى
                                                        درجة</span>
                                                    <span class="text-lg font-extrabold">{{ $fmt($sc['max']) }}</span>
                                                    <span class="text-[10px] text-white/70 block">من
                                                        {{ $l['max'] }}</span>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200">
                                                    <span
                                                        class="text-[10px] text-slate-500 font-semibold block">المتوسط
                                                        العام</span>
                                                    <span
                                                        class="text-lg font-extrabold text-slate-900">{{ $fmt($sc['avg']) }}</span>
                                                    <span
                                                        class="text-[10px] text-emerald-700 font-bold block">{{ $fmt($sc['avg_pct'], 1) }}%</span>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200">
                                                    <span class="text-[10px] text-slate-500 font-semibold block">الوسيط
                                                        (Median)</span>
                                                    <span
                                                        class="text-lg font-extrabold text-slate-900">{{ $fmt($sc['median']) }}</span>
                                                    <span class="text-[10px] text-slate-400 block">قيمة منتصف
                                                        الترتيب</span>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200">
                                                    <span class="text-[10px] text-slate-500 font-semibold block">أدنى
                                                        درجة</span>
                                                    <span
                                                        class="text-lg font-extrabold text-slate-900">{{ $fmt($sc['min']) }}</span>
                                                </div>
                                                <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200">
                                                    <span
                                                        class="text-[10px] text-slate-500 font-semibold block">الانحراف
                                                        المعياري</span>
                                                    <span
                                                        class="text-lg font-extrabold text-slate-900">{{ $fmt($sc['std']) }}</span>
                                                </div>
                                                <div
                                                    class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200 flex flex-col justify-between text-xs">
                                                    <div class="flex justify-between">
                                                        <span class="text-blue-700 font-bold">م. الذكور:</span>
                                                        <span
                                                            class="font-extrabold text-blue-900">{{ $fmt($l['avg_male']) }}</span>
                                                    </div>
                                                    <div
                                                        class="flex justify-between mt-1 pt-1 border-t border-slate-200">
                                                        <span class="text-rose-700 font-bold">م. الإناث:</span>
                                                        <span
                                                            class="font-extrabold text-rose-900">{{ $fmt($l['avg_female']) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-slate-100">
                                            <p class="text-xs font-extrabold text-slate-800 mb-3">التوزيع التكراري
                                                لفئات الدرجات</p>
                                            <div class="space-y-2.5">
                                                @foreach ($l['bins'] as $bin)
                                                    <div>
                                                        <div class="flex items-center justify-between text-xs mb-1">
                                                            <span
                                                                class="font-semibold text-slate-700">{{ $bin['label'] }}</span>
                                                            <span class="font-mono text-xs">
                                                                <strong
                                                                    class="text-slate-900">{{ number_format($bin['count']) }}</strong>
                                                                <span
                                                                    class="text-slate-400">({{ $bin['pct'] }}%)</span>
                                                            </span>
                                                        </div>
                                                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                                            <div class="h-full rounded-full {{ $accentBar }}"
                                                                style="width: {{ $bin['width'] }}%"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- ================= 3. جدول الأداء التفصيلي للجان المركز (Wide Layout) ================= --}}
                <section class="space-y-4 print-break-inside-avoid">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="h-5 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                            <h2 class="text-base md:text-lg font-extrabold text-slate-900">ثالثاً: الأداء التفصيلي
                                لجميع لجان المركز</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">إجمالي اللجان:
                            {{ $center->committees->count() }}</span>
                    </div>

                    <div class="bg-white rounded-2xl ring-1 ring-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-right border-collapse text-xs">
                                <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-extrabold">
                                    <tr>
                                        <th class="px-5 py-4">اللجنة</th>
                                        <th class="px-4 py-4">المرحلة</th>
                                        <th class="px-4 py-4 text-center">الفئة</th>
                                        <th class="px-4 py-4 text-center">المستمرون</th>
                                        <th class="px-4 py-4 text-center">حضروا</th>
                                        <th class="px-4 py-4 text-center">المنسحبون</th>
                                        <th class="px-6 py-4 min-w-[160px]">نسبة الإنجاز</th>
                                        <th class="px-4 py-4 text-center bg-indigo-50 text-indigo-950">المستوى الأول
                                            (متوسط / أعلى / عدد)</th>
                                        <th class="px-4 py-4 text-center bg-violet-50 text-violet-950">المستوى الثاني
                                            (متوسط / أعلى / عدد)</th>
                                        <th class="px-4 py-4 text-center">الحالة</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-200">
                                    @forelse ($committeeRows as $row)
                                        @php $committee = $row['committee']; @endphp
                                        <tr
                                            class="{{ $committee->active ? 'hover:bg-slate-50/80' : 'bg-slate-50/50 text-slate-400' }}">
                                            <td class="px-5 py-4 font-extrabold text-slate-900 text-sm">
                                                {{ $committee->title }}
                                                <span
                                                    class="block text-[10px] font-mono text-slate-400 font-normal">#{{ $committee->id }}</span>
                                            </td>

                                            <td class="px-4 py-4 font-semibold text-slate-700">
                                                {{ $committee->stage?->title ?? '—' }}
                                            </td>

                                            <td class="px-4 py-4 text-center font-semibold text-slate-700">
                                                {{ $committee->gender ? __($committee->gender) : '—' }}
                                            </td>

                                            <td class="px-4 py-4 text-center font-extrabold text-slate-900 text-sm">
                                                {{ number_format($row['total']) }}
                                            </td>

                                            <td class="px-4 py-4 text-center font-bold text-sky-700 text-sm">
                                                {{ number_format($row['attended']) }}
                                            </td>

                                            <td class="px-4 py-4 text-center font-semibold text-amber-700 text-sm">
                                                {{ number_format($row['withdraw']) }}
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-between text-xs font-bold mb-1">
                                                    <span class="text-emerald-700">{{ $row['bar'] }}%</span>
                                                    <span class="text-slate-500">{{ number_format($row['finished']) }}
                                                        / {{ number_format($row['total']) }}</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                                    <div class="h-full rounded-full bg-emerald-500"
                                                        style="width: {{ $row['bar'] }}%"></div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-center bg-indigo-50/30 font-mono">
                                                @if ($row['l1']['count'] > 0)
                                                    <span
                                                        class="font-extrabold text-indigo-900 text-sm">{{ $fmt($row['l1']['avg']) }}</span>
                                                    <span class="text-slate-400">/</span>
                                                    <span
                                                        class="font-bold text-slate-700">{{ $fmt($row['l1']['max']) }}</span>
                                                    <span
                                                        class="block text-[10px] text-slate-500">({{ $row['l1']['count'] }}
                                                        متسابق)</span>
                                                @else
                                                    <span class="text-slate-300">—</span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-center bg-violet-50/30 font-mono">
                                                @if ($row['l2']['count'] > 0)
                                                    <span
                                                        class="font-extrabold text-violet-900 text-sm">{{ $fmt($row['l2']['avg']) }}</span>
                                                    <span class="text-slate-400">/</span>
                                                    <span
                                                        class="font-bold text-slate-700">{{ $fmt($row['l2']['max']) }}</span>
                                                    <span
                                                        class="block text-[10px] text-slate-500">({{ $row['l2']['count'] }}
                                                        متسابق)</span>
                                                @else
                                                    <span class="text-slate-300">—</span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-center">
                                                @if ($committee->active)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-emerald-200">
                                                        نشطة
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                                        متوقفة
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10"
                                                class="px-6 py-12 text-center text-slate-500 font-semibold">
                                                لا توجد لجان مسجلة في هذا المركز
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ================= 4. سجل الإنجاز اليومي ================= --}}
                @if ($finishedByDate->isNotEmpty())
                    <section class="space-y-4 print-break-inside-avoid">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                            <div class="flex items-center gap-2.5">
                                <span class="h-5 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                                <h2 class="text-base md:text-lg font-extrabold text-slate-900">رابعاً: السجل الزمني
                                    لإنهاء المسابقة حسب الأيام</h2>
                            </div>
                            <span class="text-xs font-bold text-emerald-700">الإجمالي المنجز:
                                {{ number_format($k('finished_total')) }} متسابق</span>
                        </div>

                        <div class="bg-white rounded-2xl ring-1 ring-slate-200 shadow-sm overflow-hidden">
                            <table class="w-full text-right border-collapse text-xs">
                                <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 font-extrabold">
                                    <tr>
                                        <th class="px-5 py-3.5">التاريخ</th>
                                        <th class="px-4 py-3.5">اليوم</th>
                                        <th class="px-4 py-3.5 text-center">الإجمالي</th>
                                        <th class="px-4 py-3.5 text-center">الذكور</th>
                                        <th class="px-4 py-3.5 text-center">الإناث</th>
                                        <th class="px-4 py-3.5 text-center">المستوى الأول</th>
                                        <th class="px-4 py-3.5 text-center">المستوى الثاني</th>
                                        <th class="px-6 py-3.5 w-1/3">المؤشر النسبي</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($finishedByDate as $item)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-5 py-3.5 font-mono font-bold text-slate-900" dir="ltr">
                                                {{ $item['date'] }}</td>
                                            <td class="px-4 py-3.5 font-semibold text-slate-700">{{ $item['day_ar'] }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-center font-extrabold text-emerald-700 text-sm">
                                                {{ number_format($item['total']) }} {{ $item['label'] }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center font-bold text-blue-700">
                                                {{ number_format($item['males']) }}</td>
                                            <td class="px-4 py-3.5 text-center font-bold text-rose-700">
                                                {{ number_format($item['females']) }}</td>
                                            <td class="px-4 py-3.5 text-center font-semibold text-indigo-700">
                                                {{ number_format($item['level_1']) }}</td>
                                            <td class="px-4 py-3.5 text-center font-semibold text-violet-700">
                                                {{ number_format($item['level_2']) }}</td>
                                            <td class="px-6 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex-1 h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                                        <div class="h-full rounded-full bg-emerald-500"
                                                            style="width: {{ $item['width'] }}%"></div>
                                                    </div>
                                                    <span
                                                        class="w-12 text-left font-mono text-xs font-bold text-slate-600">{{ $item['pct'] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

            </div>
        </main>

        {{-- تذييل الصفحة الرسمي عند الطباعة --}}
        <footer
            class="w-full px-6 lg:px-12 py-6 mt-12 bg-white border-t border-slate-200 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — نظام إدارة المسابقات واللجان</span>
            <span dir="ltr">تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</span>
        </footer>
    </div>
</body>

</html>
