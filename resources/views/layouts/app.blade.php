<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title ?? 'مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="brand-shell bg-islamic-pattern font-tajawal text-brand-teal-950 antialiased">
    @php
        $user = Auth::user();
        $currentStage = \App\Models\Stage::whereActive(1)->latest('id')->first();
        $navItemClass = function (bool $active): string {
            return $active
                ? 'group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-l from-brand-gold-400/25 to-brand-teal-500/20 text-white border border-brand-gold-400/45 shadow-sm transition-all'
                : 'group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-brand-teal-100/85 hover:text-white hover:bg-white/10 border border-transparent transition-all';
        };
        $navIconClass = function (bool $active): string {
            return $active
                ? 'w-5 h-5 shrink-0 text-brand-gold-300'
                : 'w-5 h-5 shrink-0 text-brand-teal-300 group-hover:text-brand-gold-300 transition-colors';
        };
    @endphp

    <div x-data="{ sidebarOpen: false }" class="min-h-screen relative flex">

        {{-- Mobile Backdrop --}}
        <div x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-brand-teal-950/70 backdrop-blur-xs z-40 lg:hidden" x-cloak>
        </div>

        {{-- Architectural Deep Teal Sidebar --}}
        <nav :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
            class="sidebar-scroll fixed top-0 right-0 z-50 h-screen w-72 bg-islamic-pattern-dark text-white shadow-2xl border-l border-brand-gold-400/25 transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:translate-x-0 print:hidden overflow-y-auto shrink-0">

            <div class="min-h-full p-4 flex flex-col">

                {{-- Mobile Close --}}
                <div class="lg:hidden flex justify-end mb-2">
                    <button @click="sidebarOpen = false"
                        class="p-1.5 rounded-lg text-brand-teal-200 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Official Brand Logo --}}
                <a href="/"
                    class="block bg-white/95 rounded-2xl p-3 shadow-lg border border-brand-gold-400/50 hover:shadow-xl transition-all mb-4">
                    <img src="{{ asset('logo.png') }}" alt="شعار المسابقة" class="h-12 w-auto mx-auto object-contain">
                </a>

                {{-- User Profile Card --}}
                <div class="rounded-2xl p-3.5 mb-4 bg-white/[0.07] border border-brand-gold-400/25 backdrop-blur-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-gold-300 to-brand-gold-500 text-brand-teal-950 font-extrabold text-sm flex items-center justify-center shrink-0 shadow-sm">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-white font-bold text-sm truncate" title="{{ $user->name }}">
                                {{ $user->name }}
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-brand-gold-400/20 text-brand-gold-200 border border-brand-gold-400/35">
                                    {{ __($user->user_type) }}
                                </span>
                                @if ($currentStage)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-brand-teal-500/30 text-brand-teal-100 border border-brand-teal-400/30 truncate max-w-[140px]">
                                        {{ $currentStage->title }}
                                    </span>
                                @endif
                            </div>
                            @if ($user->national_id)
                                <div class="text-brand-teal-200/80 text-[11px] mt-1.5 flex items-center gap-1">
                                    <x-svgicon.brand-icon name="id-card" class="w-3.5 h-3.5 text-brand-gold-300/80" />
                                    <span>{{ $user->national_id }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Dashboard Primary Button --}}
                <div class="pb-4 mb-3 border-b border-white/10">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-center gap-2.5 w-full px-4 py-3 rounded-xl font-bold text-sm transition-all active:scale-[0.98] shadow-md
                        {{ request()->routeIs('dashboard') ? 'bg-gradient-to-l from-brand-gold-300 to-brand-gold-500 text-brand-teal-950' : 'bg-brand-teal-600/80 hover:bg-brand-teal-500 text-white border border-brand-gold-400/30' }}">
                        <x-svgicon.brand-icon name="home" class="w-5 h-5" />
                        <span>الرئيسية</span>
                    </a>
                </div>

                {{-- Navigation Links --}}
                <ul class="space-y-1 flex-1 pb-6">
                    @if ($user->user_type == 'admin')
                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-2 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>الإدارة</span>
                        </li>

                        <li>
                            <a href="{{ route('stage.index') }}" class="{{ $navItemClass(request()->routeIs('stage.*')) }}">
                                <x-svgicon.brand-icon name="stages" class="{{ $navIconClass(request()->routeIs('stage.*')) }}" />
                                <span>التصفيات</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('center.index') }}" class="{{ $navItemClass(request()->routeIs('center.*')) }}">
                                <x-svgicon.brand-icon name="centers" class="{{ $navIconClass(request()->routeIs('center.*')) }}" />
                                <span>المراكز</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('committee.index') }}" class="{{ $navItemClass(request()->routeIs('committee.*')) }}">
                                <x-svgicon.brand-icon name="committees" class="{{ $navIconClass(request()->routeIs('committee.*')) }}" />
                                <span>اللجان</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('user.index') }}" class="{{ $navItemClass(request()->routeIs('user.*')) }}">
                                <x-svgicon.brand-icon name="users" class="{{ $navIconClass(request()->routeIs('user.*')) }}" />
                                <span>مستخدمو النظام</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('student.index') }}" class="{{ $navItemClass(request()->routeIs('student.index', 'student.create', 'student.edit')) }}">
                                <x-svgicon.brand-icon name="students" class="{{ $navIconClass(request()->routeIs('student.index', 'student.create', 'student.edit')) }}" />
                                <span>المتسابقون</span>
                            </a>
                        </li>

                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>معايير التقييم</span>
                        </li>

                        <li>
                            <a href="{{ route('evaluation_element.index', 1) }}" class="{{ $navItemClass(request()->is('evaluation_element/index/1')) }}">
                                <x-svgicon.brand-icon name="evaluation" class="{{ $navIconClass(request()->is('evaluation_element/index/1')) }}" />
                                <span>عناصر التقييم - المستوى الأول</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('evaluation_element.index', 2) }}" class="{{ $navItemClass(request()->is('evaluation_element/index/2')) }}">
                                <x-svgicon.brand-icon name="evaluation" class="{{ $navIconClass(request()->is('evaluation_element/index/2')) }}" />
                                <span>عناصر التقييم - المستوى الثاني</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array($user->user_type, ['question_admin', 'admin']))
                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>الأسئلة والباقات</span>
                        </li>

                        <li>
                            <a href="{{ route('questionset.index', 1) }}" class="{{ $navItemClass(request()->is('questionset/index/1')) }}">
                                <x-svgicon.brand-icon name="questions" class="{{ $navIconClass(request()->is('questionset/index/1')) }}" />
                                <span>باقات الأسئلة - المستوى الأول</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('questionset.index', 2) }}" class="{{ $navItemClass(request()->is('questionset/index/2')) }}">
                                <x-svgicon.brand-icon name="questions" class="{{ $navIconClass(request()->is('questionset/index/2')) }}" />
                                <span>باقات الأسئلة - المستوى الثاني</span>
                            </a>
                        </li>
                    @endif

                    @if ($user->user_type == 'organizer')
                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>المتسابقون</span>
                        </li>

                        <li>
                            <a href="{{ route('student.attendance_index') }}" class="{{ $navItemClass(request()->routeIs('student.attendance_index')) }}">
                                <x-svgicon.brand-icon name="attendance" class="{{ $navIconClass(request()->routeIs('student.attendance_index')) }}" />
                                <span>المتسابقون - تسجيل الحضور</span>
                            </a>
                        </li>
                    @endif

                    @if ($user->user_type == 'admin')
                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>الحضور</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.student.female_attendance_index') }}" class="{{ $navItemClass(request()->routeIs('admin.student.female_attendance_index')) }}">
                                <x-svgicon.brand-icon name="attendance" class="{{ $navIconClass(request()->routeIs('admin.student.female_attendance_index')) }}" />
                                <span>تسجيل الحضور (الإناث)</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array($user->user_type, ['judge', 'admin']))
                        <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                            <span>التحكيم والنتائج</span>
                        </li>

                        <li>
                            <a href="{{ route('student.present_index') }}" class="{{ $navItemClass(request()->routeIs('student.present_index')) }}">
                                <x-svgicon.brand-icon name="present" class="{{ $navIconClass(request()->routeIs('student.present_index')) }}" />
                                <span>المتسابقون الحاضرون</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array($user->user_type, ['admin']))
                        <li>
                            <a href="{{ route('finished_student_list') }}" class="{{ $navItemClass(request()->routeIs('finished_student_list')) }}">
                                <x-svgicon.brand-icon name="results" class="{{ $navIconClass(request()->routeIs('finished_student_list')) }}" />
                                <span>النتائج النهائية</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('statistic_dashboard') }}" class="{{ $navItemClass(request()->routeIs('statistic_dashboard')) }}">
                                <x-svgicon.brand-icon name="statistics" class="{{ $navIconClass(request()->routeIs('statistic_dashboard')) }}" />
                                <span>الإحصائيات العامة</span>
                            </a>
                        </li>
                    @endif

                    {{-- Program Guide (PDF) Link --}}
                    <li class="flex items-center gap-2 text-[11px] uppercase tracking-wider text-brand-gold-300/90 font-bold px-2.5 pt-4 pb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-400"></span>
                        <span>المساعدة والتوثيق</span>
                    </li>
                    <li>
                        <a href="{{ route('program.guide') }}" target="_blank"
                            class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-brand-gold-200 hover:text-white bg-brand-gold-400/10 hover:bg-brand-gold-400/20 border border-brand-gold-400/30 transition-all">
                            <x-svgicon.brand-icon name="guide" class="w-5 h-5 shrink-0 text-brand-gold-300" />
                            <span>دليل البرنامج (PDF)</span>
                        </a>
                    </li>
                </ul>

                {{-- Logout Button --}}
                <div class="pt-3 border-t border-white/10 mt-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 bg-rose-500/15 hover:bg-rose-500/25 text-rose-200 hover:text-white rounded-xl text-xs font-bold transition-all border border-rose-400/25 active:scale-95">
                            <x-svgicon.brand-icon name="logout" class="w-4 h-4 shrink-0" />
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Top Institutional Bar --}}
            <header class="print:hidden bg-white/90 backdrop-blur-md border-b border-brand-sand-200 sticky top-0 z-30 shadow-xs">
                <div class="px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = true"
                            class="lg:hidden p-2 rounded-xl text-brand-teal-800 bg-brand-teal-50 hover:bg-brand-teal-100 border border-brand-teal-200 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2.5 min-w-0">
                            <x-svgicon.rosette class="w-6 h-6 shrink-0 hidden sm:block" />
                            <div class="min-w-0">
                                <div class="font-bold text-sm sm:text-base text-brand-teal-900 truncate">
                                    {{ $title ?? 'مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته' }}
                                </div>
                                @if ($currentStage)
                                    <div class="text-[11px] text-brand-gold-700 font-medium truncate">
                                        المرحلة النشطة: {{ $currentStage->title }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('program.guide') }}" target="_blank"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-brand-gold-50 text-brand-gold-800 border border-brand-gold-200 hover:bg-brand-gold-100 transition">
                            <x-svgicon.brand-icon name="guide" class="w-4 h-4 text-brand-gold-600" />
                            <span>دليل الاستخدام</span>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-3 sm:p-5 lg:p-6">
                <div class="max-w-7xl mx-auto">
                    @include('layouts._message')
                    {{ $slot }}
                </div>
            </main>
        </div>

    </div>
</body>

</html>
