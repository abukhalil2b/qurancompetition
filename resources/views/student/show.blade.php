<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title ?? 'تفاصيل المتسابق - مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته' }}</title>
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

        .g-btn {
            transition: all 0.2s ease-in-out;
        }

        .g-btn:active {
            transform: scale(0.97);
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex flex-col">

        {{-- Top app bar --}}
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
                <a href="{{ route('student.attendance_index') }}" class="flex items-center gap-3 text-gray-800 hover:text-blue-600 transition-colors">
                    <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold leading-none mt-1">العودة للقائمة</span>
                </a>

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
        </header>

        <main class="flex-1 w-full max-w-4xl mx-auto px-4 py-6 space-y-6">

            {{-- Student details --}}
            <section class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path opacity="0.5" d="M12 12c2.2091 0 4-1.7909 4-4s-1.7909-4-4-4-4 1.7909-4 4 1.7909 4 4 4z" />
                            <path d="M4 20c0-3.3137 3.5817-6 8-6s8 2.6863 8 6v1H4v-1z" />
                        </svg>
                    </span>
                    <h2 class="text-xl font-bold text-gray-800 mt-0.5">تفاصيل المتسابق</h2>
                </div>

                <div class="p-6">
                    @php
                        $age = $student->dob ? \Carbon\Carbon::parse($student->dob)->age : '-';
                    @endphp

                    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-5">

                        <div class="sm:col-span-2 md:col-span-3 pb-4 border-b border-gray-100">
                            <dt class="text-xs font-bold text-gray-400 mb-1">الاسم الكامل</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $student->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">الهاتف</dt>
                            <dd class="text-gray-800 font-semibold" dir="ltr">{{ $student->phone ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">الرقم المدني</dt>
                            <dd class="text-gray-800 font-semibold">{{ $student->national_id ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">الجنس</dt>
                            <dd class="text-gray-800 font-semibold">{{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">الجنسية</dt>
                            <dd class="text-gray-800 font-semibold">{{ $student->nationality ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">العمر / الميلاد</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-700 font-bold">
                                    {{ $age }} سنة
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-bold text-gray-400 mb-1">السكن</dt>
                            <dd class="text-gray-800 text-sm font-semibold">
                                {{ $student->state ?? '-' }} / {{ $student->wilaya ?? '-' }}
                                @if ($student->qarya)
                                    <div class="text-gray-500 font-normal text-xs mt-0.5">{{ $student->qarya }}</div>
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-2 md:col-span-3">
                            <dt class="text-xs font-bold text-gray-400 mb-1">ملحوظة</dt>
                            <dd class="text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-200 font-medium">
                                {{ $student->note ?: 'لا توجد ملاحظات مسجلة' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- Attendance confirmation --}}
            <section class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="mb-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">تأكيد الحضور للمسابقة</h3>
                    <div class="flex flex-wrap items-center gap-2">
                        @if(!empty($committee))
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-700 font-medium">
                            {{ $committee->title }} ({{ __($committee->gender) }})
                        </span>
                        @endif
                        @if(!empty($stage))
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-700 font-medium">
                            {{ $stage->title }}
                        </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-50 text-sm font-bold text-blue-700 border border-blue-100">
                            مستوى: {{ $competition->level }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('competition.student.present') }}" method="POST">
                    @csrf
                    <input type="hidden" name="competition_id" value="{{ $competition->id }}">

                    <button type="submit"
                        class="g-btn w-full sm:w-auto inline-flex justify-center items-center gap-2 h-12 px-8 rounded-xl
                               bg-blue-600 hover:bg-blue-700 text-white text-base font-bold shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تأكيد الحضور الآن
                    </button>
                </form>
            </section>

        </main>
    </div>
</body>
</html>