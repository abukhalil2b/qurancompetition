<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl border border-brand-sand-200 bg-white">

            {{-- Right Architectural Panel (Visual Identity) --}}
            <div class="hidden lg:flex lg:col-span-5 relative flex-col justify-between p-8 text-white bg-brand-teal-900 overflow-hidden">
                <img src="{{ asset('images/hero-arch.jpg') }}" alt=""
                    class="absolute inset-0 w-full h-full object-cover opacity-45">
                <div class="absolute inset-0 bg-gradient-to-b from-brand-teal-950/85 via-brand-teal-900/60 to-brand-teal-950/90"></div>

                <div class="relative z-10 flex items-center gap-2">
                    <x-svgicon.rosette class="w-6 h-6" />
                    <span class="text-xs font-bold text-brand-gold-200 tracking-wide">بوابة الدخول الموحدة</span>
                </div>

                <div class="relative z-10 my-auto py-8 text-center">
                    <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-brand-gold-400/20 border border-brand-gold-400/45 text-brand-gold-200 text-xs font-bold mb-4">
                        لجان التحكيم والإدارة والتنظيم
                    </div>
                    <h2 class="font-display text-2xl font-bold leading-relaxed mb-3">
                        مسابقة مؤسسة الشيخ مستهيل<br>للقرآن الكريم وقراءاته
                    </h2>
                    <p class="text-xs text-brand-teal-100/85 leading-relaxed">
                        منصة إلكترونية متكاملة لإدارة التصفيات، تسجيل حضور المتسابقين، سحب باقات الأسئلة، والتحكيم المباشر بدقة وشفافية.
                    </p>
                </div>

                <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-xs text-brand-gold-200">
                    <a href="{{ route('welcome') }}" class="hover:text-white transition">الصفحة الرئيسية</a>
                    <a href="{{ route('program.guide') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                        <x-svgicon.brand-icon name="guide" class="w-4 h-4" />
                        <span>دليل الاستخدام</span>
                    </a>
                </div>
            </div>

            {{-- Left Login Form Panel --}}
            <div class="lg:col-span-7 p-7 sm:p-10 flex flex-col justify-center">

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        @include('layouts._logo')
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-brand-teal-900">
                        تسجيل الدخول إلى النظام
                    </h1>
                    <p class="text-sm text-brand-teal-700/80 mt-1">
                        أدخل الرقم المدني وكلمة المرور للمتابعة
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- National ID --}}
                    <div>
                        <label for="national_id" class="block text-sm font-bold text-brand-teal-900 mb-2">
                            الرقم المدني
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-brand-teal-600 pointer-events-none">
                                <x-svgicon.brand-icon name="id-card" class="w-5 h-5" />
                            </span>
                            <input
                                id="national_id"
                                type="text"
                                name="national_id"
                                value="{{ old('national_id') }}"
                                required
                                autofocus
                                placeholder="أدخل الرقم المدني"
                                class="w-full pr-11 pl-4 py-3.5 bg-brand-sand-50 border border-brand-sand-200 rounded-xl text-brand-teal-950 placeholder-brand-teal-700/40 text-sm font-medium focus:bg-white transition">
                        </div>
                        @error('national_id')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-brand-teal-900">
                                كلمة المرور
                            </label>
                            <button type="button" @click="show = !show"
                                class="text-xs font-bold text-brand-gold-700 hover:text-brand-teal-700 transition">
                                <span x-text="show ? 'إخفاء كلمة المرور' : 'عرض كلمة المرور'">عرض كلمة المرور</span>
                            </button>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-brand-teal-600 pointer-events-none">
                                <x-svgicon.brand-icon name="lock" class="w-5 h-5" />
                            </span>
                            <input
                                id="password"
                                :type="show ? 'text' : 'password'"
                                type="password"
                                name="password"
                                required
                                placeholder="أدخل كلمة المرور"
                                class="w-full pr-11 pl-4 py-3.5 bg-brand-sand-50 border border-brand-sand-200 rounded-xl text-brand-teal-950 placeholder-brand-teal-700/40 text-sm font-medium focus:bg-white transition">
                        </div>
                        @error('password')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit" class="btn-brand-primary w-full py-3.5 text-base shadow-md">
                            <span>دخول إلى النظام</span>
                            <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>

                    <div class="pt-4 border-t border-brand-sand-100 flex items-center justify-between text-xs">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-semibold text-brand-teal-700 hover:text-brand-gold-700 transition">
                                نسيت كلمة المرور؟
                            </a>
                        @else
                            <span></span>
                        @endif
                        <a href="{{ route('program.guide') }}" class="font-semibold text-brand-gold-700 hover:text-brand-teal-800 inline-flex items-center gap-1 transition">
                            <x-svgicon.brand-icon name="guide" class="w-4 h-4" />
                            <span>دليل البرنامج (PDF)</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
