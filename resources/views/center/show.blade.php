@php
    // ---------- helper: read a KPI safely ----------
    $k = fn(string $key) => (int) ($kpi[$key] ?? 0);

    $pct = fn(int $n, int $of) => $of > 0 ? round(($n / $of) * 100, 1) . '%' : '0%';

    // ---------- Icons ----------
    $icons = [
        'users' => 'M17 20h5v-2a3 3 0 00-5.196-2.04M17 20H7m10 0v-2c0-.73-.195-1.415-.536-2M7 20H2v-2a3 3 0 015.196-2.04M7 20v-2c0-.73.195-1.415.536-2m0 0a5.002 5.002 0 019.928 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'withdraw' => 'M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6',
        'trophy' => 'M8 21h8m-4-4v4M7 4h10v5a5 5 0 01-10 0V4zM7 6H4v2a4 4 0 004 4m9-6h3v2a4 4 0 01-4 4',
        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1',
    ];

    // ---------- Stat groups ----------
    // Each card: label, total, male, female (null = no gender split), color, icon|glyph, badge
    $totalMale = $k('l1_male') + $k('l2_male');
    $totalFemale = $k('l1_female') + $k('l2_female');

    $statGroups = [
        [
            'title' => 'نظرة عامة',
            'hint' => 'جميع متسابقي المركز (المنسحبون يظهرون في بطاقة مستقلة)',
            'cols' => 'lg:grid-cols-2',
            'cards' => [
                [
                    'label' => 'إجمالي المتسابقين',
                    'total' => $k('total'),
                    'male' => $totalMale,
                    'female' => $totalFemale,
                    'color' => 'slate',
                    'icon' => 'users',
                ],
                [
                    'label' => 'المنسحبين',
                    'total' => $k('withdraw'),
                    'male' => null,
                    'female' => null,
                    'color' => 'amber',
                    'icon' => 'withdraw',
                ],
            ],
        ],
        [
            'title' => 'المستويات',
            'hint' => 'توزيع المتسابقين حسب المستوى والجنس',
            'cols' => 'lg:grid-cols-2',
            'cards' => [
                [
                    'label' => 'المستوى الأول',
                    'total' => $k('l1_total'),
                    'male' => $k('l1_male'),
                    'female' => $k('l1_female'),
                    'color' => 'indigo',
                    'glyph' => '1',
                    'badge' => $pct($k('l1_total'), $k('total')),
                ],
                [
                    'label' => 'المستوى الثاني',
                    'total' => $k('l2_total'),
                    'male' => $k('l2_male'),
                    'female' => $k('l2_female'),
                    'color' => 'violet',
                    'glyph' => '2',
                    'badge' => $pct($k('l2_total'), $k('total')),
                ],
            ],
        ],
        [
            'title' => 'إنهاء المسابقة',
            'hint' => 'من أنهوا المسابقة في كل الأيام وفي اليوم الحالي',
            'cols' => 'lg:grid-cols-2',
            'cards' => [
                [
                    'label' => 'أنهوا المسابقة (كل الأيام)',
                    'total' => $k('finished_total'),
                    'male' => $k('finished_male'),
                    'female' => $k('finished_female'),
                    'color' => 'emerald',
                    'icon' => 'trophy',
                ],
                [
                    'label' => 'أنهوا المسابقة اليوم',
                    'total' => $k('today_total'),
                    'male' => $k('today_male'),
                    'female' => $k('today_female'),
                    'color' => 'teal',
                    'icon' => 'calendar',
                ],
            ],
        ],
    ];

    $cardStyles = [
        'slate' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'badge' => 'bg-slate-100 text-slate-700', 'num' => 'text-slate-900'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'badge' => 'bg-amber-100 text-amber-700', 'num' => 'text-amber-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'badge' => 'bg-indigo-100 text-indigo-700', 'num' => 'text-slate-900'],
        'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200', 'badge' => 'bg-violet-100 text-violet-700', 'num' => 'text-slate-900'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-700', 'num' => 'text-emerald-600'],
        'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-200', 'badge' => 'bg-teal-100 text-teal-700', 'num' => 'text-teal-600'],
    ];

    // ---------- Arabic plural for "متسابق" ----------
    $arabicCount = fn(int $n) => match (true) {
        $n === 1 => 'متسابق',
        $n === 2 => 'متسابقان',
        $n >= 3 && $n <= 10 => 'متسابقين',
        default => 'متسابقًا',
    };

    // ---------- Status badge styles ----------
    $statusStyles = [
        'registration' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
        'present' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-700/10',
        'with_committee' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-700/10',
        'waiting_finalization' => 'bg-violet-50 text-violet-700 ring-1 ring-inset ring-violet-700/10',
        'finish_competition' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10',
        'withdraw' => 'bg-slate-100 text-slate-500 ring-1 ring-inset ring-slate-300',
    ];

    $isAdmin = auth()->user()?->user_type === 'admin';
@endphp

<x-app-layout>
    <div class="p-4 md:p-8 space-y-8 max-w-7xl mx-auto" dir="rtl">

        {{-- ================= Header ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $center->title }}
                </h1>
                <p class="text-sm font-medium text-slate-500 mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons['building'] }}" />
                    </svg>
                    لوحة متابعة لجان ومتسابقي المركز
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-inset ring-emerald-600/20 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    {{ $center->committees->where('active', true)->count() }} نشطة
                </span>

                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold ring-1 ring-inset ring-slate-200">
                    إجمالي {{ $center->committees->count() }} لجان
                </span>
            </div>
        </div>

        {{-- ================= Statistics ================= --}}
        <div class="space-y-8">
            @foreach ($statGroups as $group)
                <section class="space-y-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">{{ $group['title'] }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $group['hint'] }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $group['cols'] }} gap-4">
                        @foreach ($group['cards'] as $card)
                            @php $style = $cardStyles[$card['color']]; @endphp

                            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-shadow">
                                {{-- Top: label + icon --}}
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold text-slate-500">{{ $card['label'] }}</span>
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border shrink-0 {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }}">
                                        @isset($card['icon'])
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$card['icon']] }}" />
                                            </svg>
                                        @else
                                            <span class="text-sm font-extrabold">{{ $card['glyph'] }}</span>
                                        @endisset
                                    </div>
                                </div>

                                {{-- Middle: total + optional percentage badge --}}
                                <div class="mt-4 flex items-baseline justify-between">
                                    <span class="text-3xl font-extrabold tracking-tight {{ $style['num'] }}">
                                        {{ number_format($card['total']) }}
                                    </span>
                                    @if (!empty($card['badge']))
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $style['badge'] }}">
                                            {{ $card['badge'] }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Bottom: gender split --}}
                                @if ($card['male'] !== null)
                                    <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2">
                                        <div class="flex items-center justify-between rounded-lg bg-blue-50 border border-blue-200 px-3 py-1.5">
                                            <span class="text-xs font-semibold text-blue-700">ذكور</span>
                                            <span class="text-sm font-extrabold text-blue-700">{{ number_format($card['male']) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-lg bg-rose-50 border border-rose-200 px-3 py-1.5">
                                            <span class="text-xs font-semibold text-rose-700">إناث</span>
                                            <span class="text-sm font-extrabold text-rose-700">{{ number_format($card['female']) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        {{-- ================= Finished by date ================= --}}
        @if ($finishedByDate->isNotEmpty())
            <div class="bg-white rounded-2xl border border-emerald-200 p-5 shadow-sm">
                <div class="flex items-center justify-between gap-2 mb-4">
                    <h3 class="text-sm font-bold text-slate-700">تفاصيل إنهاء المسابقة حسب الأيام</h3>
                    <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                        الإجمالي: {{ number_format($k('finished_total')) }}
                    </span>
                </div>

                <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach ($finishedByDate as $item)
                        <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-3">
                            <div>
                                <span class="block text-sm font-medium text-slate-700" dir="ltr">
                                    {{ $item->finish_date ? \Carbon\Carbon::parse($item->finish_date)->format('d/m/Y') : 'غير محدد' }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    ذكور {{ (int) $item->males }} · إناث {{ (int) $item->females }}
                                </span>
                            </div>

                            <span class="shrink-0 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                {{ number_format($item->total) }} {{ $arabicCount((int) $item->total) }}
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        {{-- ================= Committees ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80">
                <h2 class="font-bold text-slate-900 text-base">لجان المركز</h2>
                <p class="text-xs text-slate-500 mt-0.5">تفاصيل وتوزيع اللجان الحالية مع المراحل والمرتبة بها</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-200/80">
                        <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3.5">اللجنة</th>
                            <th class="px-6 py-3.5">المرحلة</th>
                            <th class="px-6 py-3.5">النوع / الجنس</th>
                            <th class="px-6 py-3.5 text-center">الحالة</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($center->committees as $committee)
                            <tr class="transition-colors duration-150 {{ $committee->active ? 'hover:bg-slate-50/80' : 'bg-slate-50/40 text-slate-400' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $committee->active ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons['building'] }}" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $committee->title }}</div>
                                            <div class="text-xs font-mono text-slate-400 mt-0.5">ID: #{{ $committee->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($committee->stage)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-inset ring-blue-700/10">
                                            {{ $committee->stage->title }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">غير محددة</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                    {{ $committee->gender ? __($committee->gender) : '—' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($committee->active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-inset ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            نشطة
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                            غير نشطة
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <p class="text-sm font-semibold text-slate-600">لا توجد لجان مسجلة حالياً</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= Competitors table ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">قائمة المتسابقين</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        حاضرو اليوم + المسجلون الذين لم يحضروا بعد (دون المنسحبين)
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold ring-1 ring-inset ring-teal-600/20">
                    في القائمة: {{ number_format($competitions->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 w-12 text-center">#</th>
                            <th class="px-6 py-3.5">بيانات المتسابق</th>
                            <th class="px-6 py-3.5 text-center">المستوى</th>
                            <th class="px-6 py-3.5 text-center">الحالة</th>
                            <th class="px-6 py-3.5 text-center">وقت الحضور</th>
                            <th class="px-6 py-3.5 text-center">آخر تحديث</th>
                            <th class="px-6 py-3.5 text-center">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($competitions as $competition)
                            @php $student = $competition->student; @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 text-base mb-1">
                                            {{ $student?->name ?? 'غير معروف' }}
                                        </span>
                                        <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="text-slate-400">النوع:</span>
                                                <span class="font-medium text-slate-700">
                                                    {{ $student?->gender ? __($student->gender) : '—' }}
                                                </span>
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span class="inline-flex items-center gap-1">
                                                <span class="text-slate-400">الرقم المدني:</span>
                                                <span class="font-mono font-medium text-slate-700">
                                                    {{ $student?->national_id ?? '—' }}
                                                </span>
                                            </span>
                                            @if (!empty($student?->nationality))
                                                <span class="text-slate-300">•</span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-400">الجنسية:</span>
                                                    <span class="font-medium text-slate-700">{{ $student->nationality }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        المستوى {{ $competition->level }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$competition->student_status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ __($competition->student_status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-slate-500 font-mono text-xs whitespace-nowrap" dir="ltr">
                                    {{ $competition->present_at?->format('H:i') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center font-mono text-[10px] text-slate-700 whitespace-nowrap" dir="ltr">
                                    {{ $competition->updated_at ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if ($isAdmin)
                                        <a href="{{ route('result.show', $competition->id) }}"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:border-slate-400 text-xs font-bold rounded-lg shadow-sm transition-all">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            عرض النتيجة
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm font-semibold text-slate-600">لا توجد سجلات حضور حالياً</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>