@php
    $k = fn(string $key) => (int) ($kpi[$key] ?? 0);
    $fmt = fn($v, $d = 2) => $v === null ? '—' : rtrim(rtrim(number_format((float) $v, $d), '0'), '.');

    // ---------- Icons ----------
    $icons = [
        'building' =>
            'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1',
    ];

    // ---------- Status badge styles ----------
    $statusStyles = [
        'registration' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
        'present' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-700/10',
        'with_committee' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-700/10',
        'waiting_finalization' => 'bg-violet-50 text-violet-700 ring-1 ring-inset ring-violet-700/10',
        'finish_competition' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10',
        'withdraw' => 'bg-slate-100 text-slate-500 ring-1 ring-inset ring-slate-300',
    ];

    // مراحل تظهر في قسم القائمة
    $listStatuses = ['registration', 'present', 'with_committee', 'waiting_finalization', 'finish_competition'];

    // ---------- Card tones ----------
    $tones = [
        'slate' => 'text-slate-900',
        'teal' => 'text-[#0d4b4e]',
        'amber' => 'text-amber-600',
        'sky' => 'text-sky-600',
        'emerald' => 'text-emerald-600',
        'indigo' => 'text-indigo-700',
        'violet' => 'text-violet-700',
    ];

    // ---------- Stat groups (all numbers come from the controller) ----------
    $statGroups = [
        [
            'title' => 'نظرة عامة',
            'hint' => 'الإجمالي يشمل المنسحبين، ثم غير المنسحبين بشكل مستقل',
            'cols' => 'lg:grid-cols-3',
            'cards' => [
                [
                    'label' => 'إجمالي المتسابقين',
                    'sub' => 'شامل المنسحبين',
                    'value' => $k('total_all'),
                    'tone' => 'slate',
                ],
                [
                    'label' => 'المتسابقون غير المنسحبين',
                    'sub' => 'المتسابقون المستمرون في المسابقة',
                    'value' => $k('total'),
                    'tone' => 'teal',
                    'male' => $k('male'),
                    'female' => $k('female'),
                ],
                ['label' => 'المنسحبون', 'sub' => 'انسحبوا من المسابقة', 'value' => $k('withdraw'), 'tone' => 'amber'],
                [
                    'label' => 'حضروا',
                    'sub' => 'سجّلوا حضورهم من غير المنسحبين',
                    'value' => $k('attended'),
                    'tone' => 'sky',
                ],
                [
                    'label' => 'أنهوا المسابقة',
                    'sub' => 'في كل الأيام',
                    'value' => $k('finished_total'),
                    'tone' => 'emerald',
                    'male' => $k('finished_male'),
                    'female' => $k('finished_female'),
                ],
                [
                    'label' => 'أنهوا المسابقة اليوم',
                    'sub' => 'اليوم فقط',
                    'value' => $k('today_total'),
                    'tone' => 'teal',
                    'male' => $k('today_male'),
                    'female' => $k('today_female'),
                ],
            ],
        ],
        [
            'title' => 'المستويات',
            'hint' => 'توزيع المتسابقين غير المنسحبين على المستويين',
            'cols' => 'lg:grid-cols-2',
            'cards' => collect($levelStats)
                ->map(
                    fn($l) => [
                        'label' => $l['title'],
                        'sub' => 'عدد المتسابقين غير المنسحبين في هذا المستوى',
                        'value' => $l['total'],
                        'tone' => $l['level'] === 1 ? 'indigo' : 'violet',
                        'badge' => $l['share'] . '% من إجمالي غير المنسحبين',
                        'progress' => [
                            'label' => 'أنهوا المسابقة',
                            'done' => $l['finished'],
                            'of' => $l['total'],
                            'width' => $l['finished_width'],
                        ],
                        'male' => $l['male'],
                        'female' => $l['female'],
                    ],
                )
                ->all(),
        ],
    ];

    $isAdmin = auth()->user()?->user_type === 'admin';
@endphp

<x-app-layout>
    <div class="min-h-screen bg-slate-50" dir="rtl">

        {{-- ================= Header ================= --}}
        <div class="bg-[#062426] text-white">
            <div
                class="max-w-7xl mx-auto px-4 md:px-8 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ $center->title }}</h1>
                    <p class="text-sm text-white/70 mt-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $icons['building'] }}" />
                        </svg>
                        لوحة متابعة لجان ومتسابقي المركز
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-xs font-bold ring-1 ring-white/20">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        {{ $center->committees->where('active', true)->count() }} نشطة
                    </span>
                    <span class="px-3 py-1.5 rounded-full bg-white/10 text-xs font-bold ring-1 ring-white/20">
                        إجمالي {{ $center->committees->count() }} لجان
                    </span>
                    <a href="#stats"
                        class="px-3 py-1.5 rounded-full bg-white text-[#062426] text-xs font-bold hover:bg-white/90">الإحصائيات</a>
                    <a href="#list"
                        class="px-3 py-1.5 rounded-full bg-white text-[#062426] text-xs font-bold hover:bg-white/90">قائمة
                        المتسابقين</a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-4 md:p-8 space-y-10">

            {{-- ===================================================================== --}}
            {{--                              الإحصائيات                               --}}
            {{-- ===================================================================== --}}
            <div id="stats" class="space-y-8 scroll-mt-6">

                <div class="flex items-center gap-3">
                    <span class="h-6 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                    <h2 class="text-xl font-extrabold text-[#062426]">الإحصائيات</h2>
                </div>

                {{-- ---------- Count cards ---------- --}}
                @foreach ($statGroups as $group)
                    <section class="space-y-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">{{ $group['title'] }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $group['hint'] }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 {{ $group['cols'] }} gap-4">
                            @foreach ($group['cards'] as $card)
                                <div
                                    class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $card['label'] }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $card['sub'] }}</p>
                                    </div>

                                    {{-- الرقم + الوحدة + شارة النسبة --}}
                                    <div class="mt-3 flex flex-wrap items-baseline justify-between gap-2">
                                        <p class="flex items-baseline gap-1.5">
                                            <span
                                                class="text-3xl font-extrabold tracking-tight {{ $tones[$card['tone']] }}">
                                                {{ number_format($card['value']) }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-400">متسابق</span>
                                        </p>

                                        @if (!empty($card['badge']))
                                            <span
                                                class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">
                                                {{ $card['badge'] }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- تقدم الإنهاء: X من Y --}}
                                    @if (!empty($card['progress']))
                                        @php $pg = $card['progress']; @endphp
                                        <div class="mt-4">
                                            <div class="flex items-center justify-between text-xs mb-1.5">
                                                <span class="font-semibold text-slate-600">{{ $pg['label'] }}</span>
                                                <span class="font-extrabold text-emerald-700">
                                                    {{ number_format($pg['done']) }}
                                                    <span class="font-medium text-slate-400">من</span>
                                                    {{ number_format($pg['of']) }}
                                                </span>
                                            </div>
                                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                                <div class="h-full rounded-full bg-emerald-500"
                                                    style="width: {{ $pg['width'] }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- الذكور / الإناث --}}
                                    @if (isset($card['male']))
                                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2">
                                            <div
                                                class="flex items-center justify-between rounded-lg bg-blue-50 ring-1 ring-blue-200 px-3 py-1.5">
                                                <span class="text-xs font-semibold text-blue-700">ذكور</span>
                                                <span
                                                    class="text-sm font-extrabold text-blue-700">{{ number_format($card['male']) }}</span>
                                            </div>
                                            <div
                                                class="flex items-center justify-between rounded-lg bg-rose-50 ring-1 ring-rose-200 px-3 py-1.5">
                                                <span class="text-xs font-semibold text-rose-700">إناث</span>
                                                <span
                                                    class="text-sm font-extrabold text-rose-700">{{ number_format($card['female']) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                {{-- ---------- Scores (each level separately) ---------- --}}
                <section class="space-y-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">الدرجات</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            تُحسب إحصاءات كل مستوى بشكل منفصل (المستوى الأول من 120، المستوى الثاني من 100)
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach ($levelStats as $l)
                            @php
                                $sc = $l['scores'];
                                $tone =
                                    $l['level'] === 1
                                        ? 'text-indigo-700 bg-indigo-50 ring-indigo-200'
                                        : 'text-violet-700 bg-violet-50 ring-violet-200';
                            @endphp

                            <div class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm p-5">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-sm font-extrabold text-slate-900">درجات {{ $l['title'] }}</h4>
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold ring-1 {{ $tone }}">من
                                        {{ $l['max'] }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">عدد الدرجات المرصودة:
                                    {{ number_format($sc['count']) }}</p>

                                @if ($sc['count'] === 0)
                                    <p class="py-10 text-center text-xs text-slate-400">لا توجد درجات مرصودة لهذا
                                        المستوى بعد</p>
                                @else
                                    <dl class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach ([['أعلى درجة', $sc['max'], true], ['أدنى درجة', $sc['min'], false], ['المتوسط', $sc['avg'], false], ['الوسيط', $sc['median'], false], ['الانحراف المعياري', $sc['std'], false], ['عدد الدرجات', $sc['count'], false]] as [$label, $val, $highlight])
                                            <div
                                                class="rounded-xl px-3 py-2.5 ring-1 {{ $highlight ? 'bg-[#0d4b4e] ring-[#0d4b4e] text-white' : 'bg-slate-50 ring-slate-100' }}">
                                                <dt
                                                    class="text-[11px] font-semibold {{ $highlight ? 'text-white/70' : 'text-slate-500' }}">
                                                    {{ $label }}</dt>
                                                <dd
                                                    class="text-lg font-extrabold {{ $highlight ? 'text-white' : 'text-slate-900' }}">
                                                    {{ $fmt($val) }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>

                                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                                        <div
                                            class="flex items-center justify-between rounded-lg bg-blue-50 ring-1 ring-blue-200 px-3 py-1.5">
                                            <span class="font-semibold text-blue-700">متوسط الذكور</span>
                                            <span
                                                class="font-extrabold text-blue-700">{{ $fmt($l['avg_male']) }}</span>
                                        </div>
                                        <div
                                            class="flex items-center justify-between rounded-lg bg-rose-50 ring-1 ring-rose-200 px-3 py-1.5">
                                            <span class="font-semibold text-rose-700">متوسط الإناث</span>
                                            <span
                                                class="font-extrabold text-rose-700">{{ $fmt($l['avg_female']) }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-slate-100">
                                        <p class="text-xs font-bold text-slate-700 mb-3">توزيع الدرجات</p>
                                        <div class="space-y-2.5">
                                            @foreach ($l['bins'] as $bin)
                                                <div>
                                                    <div class="flex items-center justify-between text-xs mb-1">
                                                        <span
                                                            class="font-medium text-slate-600">{{ $bin['label'] }}</span>
                                                        <span
                                                            class="font-extrabold text-slate-900">{{ number_format($bin['count']) }}</span>
                                                    </div>
                                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                                        <div class="h-full rounded-full bg-[#0d4b4e]"
                                                            style="width: {{ $bin['width'] }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- ---------- Finished by day ---------- --}}
                @if ($finishedByDate->isNotEmpty())
                    <section class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm p-5">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <h3 class="text-sm font-bold text-slate-800">إنهاء المسابقة حسب الأيام</h3>
                            <div class="flex items-center gap-2">
                                @if ($k('avg_minutes'))
                                    <span
                                        class="px-3 py-1 rounded-lg bg-slate-50 text-slate-700 ring-1 ring-slate-200 text-xs font-bold">
                                        متوسط مدة المسابقة: {{ number_format($k('avg_minutes')) }} دقيقة
                                    </span>
                                @endif
                                <span
                                    class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 text-xs font-bold">
                                    الإجمالي: {{ number_format($k('finished_total')) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            @foreach ($finishedByDate as $item)
                                <div class="flex items-center gap-3">
                                    <span class="w-24 shrink-0 text-xs font-medium text-slate-600"
                                        dir="ltr">{{ $item['date'] }}</span>
                                    <div class="flex-1 h-5 rounded-md bg-slate-100 overflow-hidden">
                                        <div class="h-full bg-emerald-500/80" style="width: {{ $item['width'] }}%">
                                        </div>
                                    </div>
                                    <span class="w-52 shrink-0 text-xs text-slate-500">
                                        <span
                                            class="font-extrabold text-slate-800">{{ number_format($item['total']) }}</span>
                                        {{ $item['label'] }}
                                        · ذكور {{ $item['males'] }} · إناث {{ $item['females'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ---------- Data quality (admin only) ---------- --}}
                @if ($isAdmin && $k('dq_finished_no_score') + $k('dq_score_not_finished') > 0)
                    <div class="rounded-2xl bg-amber-50 ring-1 ring-amber-200 p-4 text-xs text-amber-800 space-y-1">
                        <p class="font-bold">تنبيهات جودة البيانات</p>
                        @if ($k('dq_finished_no_score'))
                            <p>• {{ $k('dq_finished_no_score') }} متسابق أنهى المسابقة دون درجة نهائية.</p>
                        @endif
                        @if ($k('dq_score_not_finished'))
                            <p>• {{ $k('dq_score_not_finished') }} متسابق لديه درجة نهائية لكن حالته ليست «أنهى
                                المسابقة».</p>
                        @endif
                    </div>
                @endif

                {{-- ---------- Committees ---------- --}}
                <div class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80">
                        <h3 class="font-bold text-slate-900 text-base">لجان المركز</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            أعداد المتسابقين، ومتوسط / أعلى درجة لكل مستوى (المستوى الأول من 120، الثاني من 100)
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead class="bg-slate-50/50 border-b border-slate-200/80">
                                <tr class="text-xs font-semibold text-slate-500 tracking-wider">
                                    <th class="px-6 py-3.5">اللجنة</th>
                                    <th class="px-4 py-3.5">المرحلة</th>
                                    <th class="px-4 py-3.5">النوع / الجنس</th>
                                    <th class="px-4 py-3.5 text-center">المتسابقون</th>
                                    <th class="px-4 py-3.5 text-center">حضروا</th>
                                    <th class="px-4 py-3.5 min-w-[150px]">أنهوا المسابقة</th>
                                    <th class="px-4 py-3.5 text-center">م1: متوسط / أعلى</th>
                                    <th class="px-4 py-3.5 text-center">م2: متوسط / أعلى</th>
                                    <th class="px-6 py-3.5 text-center">الحالة</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($committeeRows as $row)
                                    @php $committee = $row['committee']; @endphp
                                    <tr
                                        class="transition-colors duration-150 {{ $committee->active ? 'hover:bg-slate-50/80' : 'bg-slate-50/40 text-slate-400' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3.5">
                                                <div
                                                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $committee->active ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="{{ $icons['building'] }}" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 text-sm">
                                                        {{ $committee->title }}</div>
                                                    <div class="text-xs font-mono text-slate-400 mt-0.5">ID:
                                                        #{{ $committee->id }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            @if ($committee->stage)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-inset ring-blue-700/10">
                                                    {{ $committee->stage->title }}
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400 italic">غير محددة</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm font-medium text-slate-700">
                                            {{ $committee->gender ? __($committee->gender) : '—' }}
                                        </td>

                                        <td class="px-4 py-4 text-center text-sm font-bold text-slate-800">
                                            {{ number_format($row['total']) }}</td>
                                        <td class="px-4 py-4 text-center text-sm font-bold text-slate-800">
                                            {{ number_format($row['attended']) }}</td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="h-1.5 flex-1 rounded-full bg-slate-100 overflow-hidden">
                                                    <div class="h-full bg-emerald-500"
                                                        style="width: {{ $row['bar'] }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-600 whitespace-nowrap">
                                                    {{ number_format($row['finished']) }} /
                                                    {{ number_format($row['total']) }}
                                                </span>
                                            </div>
                                        </td>

                                        <td
                                            class="px-4 py-4 text-center text-sm font-bold text-indigo-700 whitespace-nowrap">
                                            {{ $fmt($row['l1']['avg']) }} / {{ $fmt($row['l1']['max']) }}
                                        </td>
                                        <td
                                            class="px-4 py-4 text-center text-sm font-bold text-violet-700 whitespace-nowrap">
                                            {{ $fmt($row['l2']['avg']) }} / {{ $fmt($row['l2']['max']) }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if ($committee->active)
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-inset ring-emerald-600/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    نشطة
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                                                    غير نشطة
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <p class="text-sm font-semibold text-slate-600">لا توجد لجان مسجلة حالياً
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ===================================================================== --}}
            {{--                           قائمة المتسابقين                            --}}
            {{-- ===================================================================== --}}
            <div id="list" class="space-y-6 scroll-mt-6">

                <div class="flex items-center gap-3">
                    <span class="h-6 w-1.5 rounded-full bg-[#0d4b4e]"></span>
                    <h2 class="text-xl font-extrabold text-[#062426]">قائمة المتسابقين</h2>
                </div>

                {{-- ---------- مراحل المتسابقين ---------- --}}
                <section class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm p-5">
                    <div class="mb-3">
                        <h3 class="text-sm font-bold text-slate-800">مراحل المتسابقين</h3>
                        <p class="text-xs text-slate-500 mt-0.5">حسب المتسابقين الظاهرين في القائمة أدناه</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                        @foreach ($listStatuses as $status)
                            <div
                                class="flex items-center justify-between rounded-xl px-3 py-2.5 {{ $statusStyles[$status] }}">
                                <span class="text-xs font-bold">{{ __($status) }}</span>
                                <span
                                    class="text-lg font-extrabold">{{ number_format($listStatusCounts[$status] ?? 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- ---------- الجدول ---------- --}}
                <div class="bg-white rounded-2xl ring-1 ring-slate-100 shadow-sm overflow-hidden">
                    <div
                        class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">أسماء المتسابقين</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                حاضرو اليوم + المسجلون الذين لم يحضروا بعد (دون المنسحبين)
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold ring-1 ring-inset ring-teal-600/20 whitespace-nowrap">
                            في القائمة: {{ number_format($competitions->count()) }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead
                                class="bg-slate-50/50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 w-12 text-center">#</th>
                                    <th class="px-6 py-3.5">بيانات المتسابق</th>
                                    <th class="px-6 py-3.5 text-center">المستوى</th>
                                    <th class="px-6 py-3.5 text-center">الحالة</th>
                                    <th class="px-6 py-3.5 text-center">وقت الحضور</th>
                                    <th class="px-6 py-3.5 text-center">وقت الإنهاء</th>
                                    <th class="px-6 py-3.5 text-center">آخر تحديث</th>
                                    <th class="px-6 py-3.5 text-center">الإجراءات</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse ($competitions as $competition)
                                    @php $student = $competition->student; @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">
                                            {{ $loop->iteration }}</td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-900 text-base mb-1">
                                                    {{ $student?->name ?? 'غير معروف' }}
                                                </span>
                                                <div
                                                    class="text-xs text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <span class="inline-flex items-center gap-1">
                                                        <span class="text-slate-400">النوع:</span>
                                                        <span
                                                            class="font-medium text-slate-700">{{ $student?->gender ? __($student->gender) : '—' }}</span>
                                                    </span>
                                                    <span class="text-slate-300">•</span>
                                                    <span class="inline-flex items-center gap-1">
                                                        <span class="text-slate-400">الرقم المدني:</span>
                                                        <span
                                                            class="font-mono font-medium text-slate-700">{{ $student?->national_id ?? '—' }}</span>
                                                    </span>
                                                    @if (!empty($student?->nationality))
                                                        <span class="text-slate-300">•</span>
                                                        <span class="inline-flex items-center gap-1">
                                                            <span class="text-slate-400">الجنسية:</span>
                                                            <span
                                                                class="font-medium text-slate-700">{{ $student->nationality }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                المستوى {{ $competition->level }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$competition->student_status] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ __($competition->student_status) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center text-slate-500 font-mono text-xs whitespace-nowrap"
                                            dir="ltr">
                                            {{ $competition->present_at?->format('H:i') ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-center text-slate-600 font-mono text-xs whitespace-nowrap"
                                            dir="ltr">
                                            {{ $competition->finished_at?->format('Y-m-d H:i') ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-center font-mono text-[10px] text-slate-700 whitespace-nowrap"
                                            dir="ltr">
                                            {{ $competition->updated_at ?? '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            @if ($isAdmin)
                                                <a href="{{ route('result.show', $competition->id) }}"
                                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:border-slate-400 text-xs font-bold rounded-lg shadow-sm transition-all">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <p class="text-sm font-semibold text-slate-600">لا توجد سجلات حضور حالياً
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
