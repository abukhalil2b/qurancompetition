<x-app-layout>
    <div class="p-4 md:p-8 space-y-8 max-w-7xl mx-auto" dir="rtl">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $center->title }}
                </h1>
                <p class="text-sm font-medium text-slate-500 mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1" />
                    </svg>
                    لوحة متابعة لجان ومتسابقي المركز
                </p>
            </div>

            {{-- Summary Badges --}}
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


        {{-- Committees Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900 text-base">
                        لجان المركز
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        تفاصيل وتوزيع اللجان الحالية مع المراحل والمرتبة بها
                    </p>
                </div>
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
                        @forelse($center->committees as $committee)
                            <tr class="transition-colors duration-150 {{ $committee->active ? 'hover:bg-slate-50/80' : 'bg-slate-50/40 text-slate-400' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-xl {{ $committee->active ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">
                                                {{ $committee->title }}
                                            </div>
                                            <div class="text-xs font-mono text-slate-400 mt-0.5">
                                                ID: #{{ $committee->id }}
                                            </div>
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
                                    {{ __($committee->gender) }}
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


        {{-- Competitors Section & KPI Stats --}}
        @php
            $statusCounts = $competitions->countBy('student_status');
            $totalCompetitions = $competitions->filter(fn($c) => $c->student_status !== 'withdraw')->count();

            // حسابات المتسابقين المطلوبة
            $maleCount = $competitions->filter(fn($c) => optional($c->student)->gender === 'male')->count();
            $femaleCount = $competitions->filter(fn($c) => optional($c->student)->gender === 'female')->count();
            
            $level1Count = $competitions->where('level', 1)->count();
            $level2Count = $competitions->where('level', 2)->count();
            
            // حساب الذين أنهوا المسابقة (إجمالي + ذكور + إناث)
            $finishedCompetitions = $competitions->where('student_status', 'finish_competition');
            $finishedTotal = $finishedCompetitions->count();
            $finishedMales = $finishedCompetitions->filter(fn($c) => optional($c->student)->gender === 'male')->count();
            $finishedFemales = $finishedCompetitions->filter(fn($c) => optional($c->student)->gender === 'female')->count();

            $statistics = [
                [
                    'label' => 'إجمالي المتسابقين',
                    'count' => $totalCompetitions,
                    'icon' => 'groups',
                    'color' => 'slate',
                    'badge' => null,
                ],
                [
                    'label' => 'ذكور',
                    'count' => $maleCount,
                    'icon' => 'male',
                    'color' => 'blue',
                    'badge' => $totalCompetitions > 0 ? round(($maleCount / $totalCompetitions) * 100, 1) . '%' : '0%',
                ],
                [
                    'label' => 'إناث',
                    'count' => $femaleCount,
                    'icon' => 'female',
                    'color' => 'rose',
                    'badge' => $totalCompetitions > 0 ? round(($femaleCount / $totalCompetitions) * 100, 1) . '%' : '0%',
                ],
                [
                    'label' => 'المستوى الأول',
                    'count' => $level1Count,
                    'icon' => 'filter_1',
                    'color' => 'indigo',
                    'badge' => $totalCompetitions > 0 ? round(($level1Count / $totalCompetitions) * 100, 1) . '%' : '0%',
                ],
                [
                    'label' => 'المستوى الثاني',
                    'count' => $level2Count,
                    'icon' => 'filter_2',
                    'color' => 'violet',
                    'badge' => $totalCompetitions > 0 ? round(($level2Count / $totalCompetitions) * 100, 1) . '%' : '0%',
                ],
            ];

            $cardStyles = [
                'slate' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'badge' => 'bg-slate-100 text-slate-700'],
                'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-700'],
                'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'badge' => 'bg-rose-100 text-rose-700'],
                'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'badge' => 'bg-indigo-100 text-indigo-700'],
                'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200', 'badge' => 'bg-violet-100 text-violet-700'],
                'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-700'],
            ];
        @endphp

        <div class="space-y-6">
            {{-- Stat Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach ($statistics as $stat)
                    @php $style = $cardStyles[$stat['color']]; @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-slate-500">
                                {{ $stat['label'] }}
                            </span>
                            <div class="p-2 rounded-xl border {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }} shrink-0">
                                @switch($stat['icon'])
                                    @case('groups')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.04M17 20H7m10 0v-2c0-.73-.195-1.415-.536-2M7 20H2v-2a3 3 0 015.196-2.04M7 20v-2c0-.73.195-1.415.536-2m0 0a5.002 5.002 0 019.928 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @break
                                    @case('male')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @break
                                    @case('female')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    @break
                                    @case('filter_1')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @break
                                    @case('filter_2')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    @break
                                @endswitch
                            </div>
                        </div>

                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                                {{ number_format($stat['count']) }}
                            </span>
                            @if ($stat['badge'] !== null)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $style['badge'] }}">
                                    {{ $stat['badge'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- بطاقة مخصصة لمن أنهوا المسابقة (المجموع + تفصيل الذكور والإناث) --}}
            <div class="bg-white rounded-2xl border border-emerald-200 p-5 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs font-bold text-slate-600">
                        أنهوا المسابقة 
                    </span>
                    <div class="p-2 rounded-xl border bg-emerald-50 text-emerald-700 border-emerald-200 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 01-10 0V4zM7 6H4v2a4 4 0 004 4m9-6h3v2a4 4 0 01-4 4"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-baseline justify-between gap-4">
                    <div class="flex items-baseline gap-3">
                        <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">
                            {{ number_format($finishedTotal) }}
                        </span>
                        <span class="text-xs font-medium text-slate-500">
                            من إجمالي المتسابقين في المركز
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">
                            👨 ذكور: {{ $finishedMales }}
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                            👩 إناث: {{ $finishedFemales }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Competitors Data Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">قائمة المتسابقين</h3>
                        <p class="text-xs text-slate-500 mt-0.5">سجل الحضور والنتائج لجميع المتسابقين المسجلين بالمركز</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold ring-1 ring-inset ring-teal-600/20">
                        الإجمالي: {{ number_format($totalCompetitions) }}
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
                                <th class="px-6 py-3.5 text-center">وقت التسجيل</th>
                                <th class="px-6 py-3.5 text-center">الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($competitions as $competition)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900 text-base mb-1">
                                                {{ $competition->student->name ?? 'غير معروف' }}
                                            </span>
                                            <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-400">النوع:</span>
                                                    <span class="font-medium text-slate-700">{{ __($competition->student->gender) ?? '—' }}</span>
                                                </span>
                                                <span class="text-slate-300">•</span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-400">الرقم المدني:</span>
                                                    <span class="font-mono font-medium text-slate-700">{{ $competition->student->national_id ?? '—' }}</span>
                                                </span>
                                                @if (!empty($competition->student->nationality))
                                                    <span class="text-slate-300">•</span>
                                                    <span class="inline-flex items-center gap-1">
                                                        <span class="text-slate-400">الجنسية:</span>
                                                        <span class="font-medium text-slate-700">{{ $competition->student->nationality }}</span>
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

                                    @php
                                        $statusStyles = [
                                            'registration' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
                                            'present' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-700/10',
                                            'with_committee' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-700/10',
                                            'finish_competition' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10',
                                        ];
                                    @endphp

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$competition->student_status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ __($competition->student_status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center text-slate-500 font-mono text-xs whitespace-nowrap" dir="ltr">
                                        {{ $competition->present_at?->format('H:i') ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if (auth()->user()?->user_type == 'admin')
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
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <p class="text-sm font-semibold text-slate-600">لا توجد سجلات حضور حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>