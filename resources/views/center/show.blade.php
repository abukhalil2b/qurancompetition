@php
    $icons = [
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1',
    ];

    $statusStyles = [
        'registration'         => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
        'present'              => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-700/10',
        'with_committee'       => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-700/10',
        'waiting_finalization' => 'bg-violet-50 text-violet-700 ring-1 ring-inset ring-violet-700/10',
        'finish_competition'   => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10',
        'withdraw'             => 'bg-slate-100 text-slate-500 ring-1 ring-inset ring-slate-300',
    ];

    $listStatuses = ['registration', 'present', 'with_committee', 'waiting_finalization', 'finish_competition'];
    $isAdmin = auth()->user()?->user_type === 'admin';
@endphp

<x-app-layout>
    <div class="min-h-screen bg-slate-50" dir="rtl">

        {{-- ================= Header ================= --}}
        <div class="bg-[#062426] text-white">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-emerald-300/90 font-semibold mb-1">
                        <a href="{{ route('center.index') }}" class="hover:underline">المراكز</a>
                        <span>/</span>
                        <span>قائمة المتسابقين</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ $center->title }}</h1>
                    <p class="text-sm text-white/70 mt-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons['building'] }}" />
                        </svg>
                        متابعة المتسابقين الحاضرين اليوم والمسجلين في قوائم الانتظار
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-xs font-bold ring-1 ring-white/20">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        {{ $center->committees->where('active', true)->count() }} لجان نشطة
                    </span>

                    <a href="{{ route('center.statistic', $center->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#041819] text-xs font-extrabold shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        التقرير الإحصائي الشامل
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-4 md:p-8 space-y-6">

            {{-- ---------- مراحل المتسابقين ---------- --}}
            <section class="bg-white rounded-2xl ring-1 ring-slate-200/70 shadow-sm p-5">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">مراحل المتسابقين الحالية</h3>
                        <p class="text-xs text-slate-500 mt-0.5">توزيع المتسابقين الظاهرين في القائمة أدناه حسب المرحلة</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach ($listStatuses as $status)
                        <div class="flex items-center justify-between rounded-xl px-3.5 py-3 {{ $statusStyles[$status] }}">
                            <span class="text-xs font-bold">{{ __($status) }}</span>
                            <span class="text-xl font-extrabold">{{ number_format($listStatusCounts[$status] ?? 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ---------- جدول المتسابقين ---------- --}}
            <div class="bg-white rounded-2xl ring-1 ring-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">أسماء المتسابقين</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            حاضرو اليوم + المسجلون الذين لم يحضروا بعد (دون المنسحبين)
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold ring-1 ring-inset ring-teal-600/20">
                        العدد في القائمة: {{ number_format($competitions->count()) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead class="bg-slate-50/50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 w-12 text-center">#</th>
                                <th class="px-6 py-3.5">بيانات المتسابق</th>
                                <th class="px-6 py-3.5 text-center">اللجنة</th>
                                <th class="px-6 py-3.5 text-center">المستوى</th>
                                <th class="px-6 py-3.5 text-center">الحالة</th>
                                <th class="px-6 py-3.5 text-center">وقت الحضور</th>
                                <th class="px-6 py-3.5 text-center">وقت البداية</th>
                                <th class="px-6 py-3.5 text-center">وقت الإنهاء</th>
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
                                                    <span class="font-medium text-slate-700">{{ $student?->gender ? __($student->gender) : '—' }}</span>
                                                </span>
                                                <span class="text-slate-300">•</span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="text-slate-400">الرقم المدني:</span>
                                                    <span class="font-mono font-medium text-slate-700">{{ $student?->national_id ?? '—' }}</span>
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

                                    <td class="px-6 py-4 text-center text-xs font-semibold text-slate-700">
                                        {{ $competition->committee?->title ?? '—' }}
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

                                    <td class="px-6 py-4 text-center text-slate-600 font-mono text-xs whitespace-nowrap" dir="ltr">
                                        {{ $competition->with_committee_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-slate-600 font-mono text-xs whitespace-nowrap" dir="ltr">
                                        {{ $competition->finished_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if ($isAdmin)
                                            <a href="{{ route('result.show', $competition->id) }}"
                                               class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:border-slate-400 text-xs font-bold rounded-lg shadow-sm transition-all">
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
                                        <p class="text-sm font-semibold text-slate-600">لا توجد سجلات حضور حالياً</p>
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