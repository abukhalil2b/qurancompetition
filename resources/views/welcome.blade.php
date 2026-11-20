<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسابقة مؤسسة الشيخ مستهيل بن أحمد بن علي المعشني الوقفية الخاصة للقرآن الكريم وقراءاته</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen relative flex flex-col justify-between font-tajawal text-white overflow-x-hidden bg-brand-teal-950">

    {{-- Architectural Background Image (Inspired by Omani Mosque Domes & Teal Archway) --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/hero-arch.jpg') }}" alt=""
            class="w-full h-full object-cover object-center scale-[1.01]">
        <div class="absolute inset-0 bg-gradient-to-b from-brand-teal-950/75 via-brand-teal-900/50 to-brand-teal-950/85"></div>
    </div>

    {{-- Top Official Institutional Header Strip --}}
    <header class="relative z-10 w-full bg-brand-teal-950/70 backdrop-blur-md border-b border-brand-gold-400/30">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <img src="{{ asset('bg.png') }}" alt="وزارة الأوقاف والشؤون الدينية ومؤسسة الشيخ مستهيل"
                class="h-10 sm:h-12 w-auto object-contain rounded-lg">
            <a href="{{ route('program.guide') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-brand-gold-400/20 hover:bg-brand-gold-400/30 border border-brand-gold-400/50 text-brand-gold-100 text-xs sm:text-sm font-bold transition">
                <x-svgicon.brand-icon name="guide" class="w-4 h-4 text-brand-gold-300" />
                <span>دليل البرنامج (PDF)</span>
            </a>
        </div>
    </header>

    {{-- Center Architectural Card --}}
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-xl">
            <div class="relative rounded-3xl p-7 sm:p-10 text-center bg-brand-teal-950/55 backdrop-blur-xl border border-brand-gold-400/35 shadow-2xl overflow-hidden">

                {{-- Subtle Top Arch Glow --}}
                <div class="pointer-events-none absolute -top-24 inset-x-0 h-44 bg-gradient-to-b from-brand-gold-400/20 to-transparent blur-2xl"></div>

                {{-- Official Logo --}}
                <div class="relative mb-6 flex justify-center">
                    @include('layouts._logo')
                </div>

                {{-- Gold Title Plaque with 8-Pointed Rosettes (Exact Inspiration from Uploaded Image) --}}
                <div class="relative inline-flex items-center justify-center gap-3 px-5 sm:px-7 py-3 rounded-2xl brand-gold-plaque text-brand-teal-950 mb-5 max-w-full">
                    <x-svgicon.rosette class="w-6 h-6 shrink-0" />
                    <span class="font-extrabold text-sm sm:text-base tracking-wide">
                        نظام إدارة وتحكيم المسابقة القرآنية
                    </span>
                    <x-svgicon.rosette class="w-6 h-6 shrink-0" />
                </div>

                {{-- Main Title --}}
                <h1 class="font-display text-2xl sm:text-4xl font-bold leading-relaxed text-white drop-shadow-sm mb-2">
                    مسابقة مؤسسة الشيخ مستهيل<br>
                    بن أحمد بن علي المعشني
                </h1>
                <p class="text-sm sm:text-base text-brand-gold-200 font-medium mb-8">
                    الوقفية الخاصة للقرآن الكريم وقراءاته
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-3 max-w-sm mx-auto">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-brand-gold py-3.5 text-base shadow-lg">
                                <x-svgicon.brand-icon name="home" class="w-5 h-5" />
                                <span>الدخول إلى لوحة التحكم</span>
                            </a>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('statistic_dashboard') }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/25 transition">
                                    <x-svgicon.brand-icon name="statistics" class="w-4 h-4 text-brand-gold-300" />
                                    <span>الإحصائيات</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-rose-100 bg-rose-500/20 hover:bg-rose-500/35 border border-rose-300/35 transition">
                                        <x-svgicon.brand-icon name="logout" class="w-4 h-4" />
                                        <span>تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn-brand-gold py-3.5 text-base shadow-lg">
                                <x-svgicon.brand-icon name="lock" class="w-5 h-5" />
                                <span>تسجيل الدخول إلى النظام</span>
                            </a>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('statistic_dashboard') }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/25 transition">
                                    <x-svgicon.brand-icon name="statistics" class="w-4 h-4 text-brand-gold-300" />
                                    <span>لوحة الإحصائيات</span>
                                </a>
                                <a href="{{ route('program.guide') }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-brand-gold-100 bg-brand-teal-800/60 hover:bg-brand-teal-700/70 border border-brand-gold-400/40 transition">
                                    <x-svgicon.brand-icon name="guide" class="w-4 h-4 text-brand-gold-300" />
                                    <span>شرح البرنامج</span>
                                </a>
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 py-4 px-4 text-center text-xs text-brand-teal-100/80 bg-brand-teal-950/60 backdrop-blur-sm border-t border-white/10">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} مسابقة مؤسسة الشيخ مستهيل بن أحمد بن علي المعشني الوقفية الخاصة للقرآن الكريم وقراءاته
    </footer>
</body>

</html>
