<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title ?? 'مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/svg" href="{{ asset('favicon.svg') }}" />

    {{-- Google fonts: Cairo --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --g-blue: #2563eb;
            --g-blue-hover: #1d4ed8;
            --g-surface: #ffffff;
            --g-bg: #f3f4f6;
            --g-outline: #e5e7eb;
            --g-text: #1f2937;
            --g-text-2: #4b5563;
        }

        body {
            font-family: 'Cairo', system-ui, sans-serif;
            background: var(--g-bg);
            color: var(--g-text);
        }

        .g-elev-1 {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        }

        .g-btn {
            transition: all 0.2s ease-in-out;
        }

        .g-btn:active {
            transform: scale(0.97);
        }

        .g-row:hover {
            background: #f9fafb;
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex flex-col">

        {{-- Top app bar --}}
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
                <a href="{{ route('student.attendance_index') }}" class="flex items-center gap-3 text-gray-800 hover:text-blue-600 transition-colors">
                    <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path opacity="0.5" d="M12 12c2.2091 0 4-1.7909 4-4s-1.7909-4-4-4-4 1.7909-4 4 1.7909 4 4 4z" />
                            <path d="M4 20c0-3.3137 3.5817-6 8-6s8 2.6863 8 6v1H4v-1z" />
                        </svg>
                    </span>
               <span class="text-red-800">{{ auth()->user()?->name }}</span>
                </a>

                <div class="flex items-center gap-3">
                    {{-- Refresh Button --}}
                    <button onclick="window.location.reload()" class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold transition-all border border-gray-200 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">تحديث</span>
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-semibold transition-all border border-red-100 active:scale-95">
                            <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">خروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-6xl mx-auto px-4 py-6"
            x-data="{
                filter: '',
                rows: @js($competitions->map(fn ($c) => mb_strtolower(($c->student->name ?? '') . ' ' . ($c->student->national_id ?? '') . ' ' . ($c->student->phone ?? '')))->values()),
                get visible() {
                    const f = this.filter.trim().toLowerCase();
                    return f === '' ? this.rows.length : this.rows.filter(r => r.includes(f)).length;
                }
            }">

            {{-- Context card --}}
            <section class="bg-white rounded-xl border border-gray-200 p-5 mb-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if (!empty($committee?->title))
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-50 text-blue-700 text-sm font-semibold border border-blue-100">
                            {{ $committee->title }}
                        </span>
                    @endif
                    @if (!empty($center))
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-50 text-gray-700 text-sm font-medium border border-gray-200">
                            {{ $center->title }}
                        </span>
                    @endif
                    @if (!empty($stage))
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-50 text-gray-700 text-sm font-medium border border-gray-200">
                            {{ $stage->title }}
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-bold text-gray-500 ml-2">المحكمون:</span>
                    @foreach ($judges as $judge)
                        <span class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-gray-100 text-sm text-gray-700 font-medium border border-gray-200">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">
                                {{ mb_substr($judge->user->name, 0, 1) }}
                            </span>
                            {{ $judge->user->name }}
                        </span>
                    @endforeach
                </div>
            </section>

            {{-- Statistics Grid --}}
            @if(isset($stats))
            <div class="grid grid-cols-3 md:grid-cols-6 gap-2 mb-6">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">إجمالي المتسابقين</span>
                    <span class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">النشطون</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $stats['active'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">بانتظار الحضور</span>
                    <span class="text-2xl font-bold text-orange-500">{{ $stats['registered'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">حضروا للمقر</span>
                    <span class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">المنسحبون</span>
                    <span class="text-2xl font-bold text-red-500">{{ $stats['withdrawn'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-semibold mb-1">أنهو</span>
                    <span class="text-2xl font-bold text-red-500">{{ $stats['finished'] }}</span>
                </div>
            </div>
            @endif

            {{-- Search & Controls --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <svg class="w-5 h-5 text-gray-400 absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3.5-3.5" />
                    </svg>
                    <input type="text" x-model="filter" dir="rtl"
                        placeholder="ابحث بالاسم، الرقم المدني، أو الهاتف..."
                        class="w-full h-12 pr-10 pl-4 rounded-xl border border-gray-300 bg-white text-gray-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all font-medium">
                </div>
            </div>

            {{-- Students table --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full" dir="rtl">
                        <thead>
                            <tr class="text-right text-sm font-bold text-gray-600 border-b border-gray-200 bg-gray-50">
                                <th class="px-5 py-4 w-12">#</th>
                                <th class="px-5 py-4">بيانات المتسابق</th>
                                <th class="px-5 py-4">المستوى</th>
                                <th class="px-5 py-4">اللجنة</th>
                                <th class="px-5 py-4">الحالة</th>
                                <th class="px-5 py-4 text-center">وقت الحضور</th>
                                <th class="px-5 py-4">الإجراء</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($competitions as $competition)
                                @php
                                    $student = $competition->student;
                                    $statusClasses = [
                                        'registration' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'present' => 'bg-green-50 text-green-700 border-green-200',
                                        'with_committee' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'withdraw' => 'bg-red-50 text-red-700 border-red-200',
                                        'waiting_finalization' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'finish_competition' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    ];
                                @endphp

                                <tr class="g-row text-right transition-colors"
                                    x-data="{ searchStr: @js(mb_strtolower(($student->name ?? '') . ' ' . ($student->national_id ?? '') . ' ' . ($student->phone ?? ''))) }"
                                    x-show="filter.trim() === '' || searchStr.includes(filter.trim().toLowerCase())">

                                    <td class="px-5 py-4 text-gray-500 font-medium">{{ $loop->iteration }}</td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="text-base font-bold text-gray-800">{{ $student->name ?? '-' }}</div>
                                        <div class="mt-1 flex items-center gap-3 text-xs text-gray-500 font-medium">
                                            <span>📞 <span dir="ltr">{{ $student->phone ?? '-' }}</span></span>
                                            <span>💳 {{ $student->national_id ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-gray-700 font-medium">{{ $competition->level ?? '-' }}</td>

                                    <td class="px-5 py-4 text-gray-700 font-medium">{{ $competition->committee?->title ?? '-' }}</td>

                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold border {{ $statusClasses[$competition->student_status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ __($competition->student_status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center" dir="ltr">
                                        @if ($competition->present_at)
                                            <div class="font-bold text-gray-800">{{ $competition->present_at->format('h:i A') }}</div>
                                            <div class="text-[11px] text-gray-400 mt-0.5 font-medium">
                                                {{ $competition->present_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-300 font-bold">—</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($competition->student_status == 'registration')
                                            <a href="{{ route('student.show', $competition->id) }}"
                                                class="g-btn inline-flex items-center h-10 px-6 rounded-lg bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-sm">
                                                تسجيل حضور
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm font-medium">تم الإجراء</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Empty state --}}
                <div x-show="visible === 0" x-cloak class="px-6 py-16 text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-gray-700">لا توجد نتائج مطابقة</p>
                    <p class="text-sm text-gray-500 mt-1">جرّب البحث بالاسم أو الرقم المدني أو رقم الهاتف بصيغة مختلفة.</p>
                    <button type="button" @click="filter = ''" class="mt-4 px-6 py-2 rounded-lg text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                        مسح البحث
                    </button>
                </div>
            </div>

        </main>
    </div>
</body>
</html>