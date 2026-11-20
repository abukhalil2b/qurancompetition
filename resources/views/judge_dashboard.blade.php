<x-app-layout>
    <div class="space-y-6">

        {{-- Judge Architectural Header --}}
        <div class="page-header-brand bg-islamic-pattern-dark">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold-400/20 border border-brand-gold-400/40 text-brand-gold-200 text-xs font-bold mb-3">
                        <x-svgicon.rosette class="w-4 h-4" />
                        <span>{{ $activeStage->title }}</span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold text-white">
                        لوحة المحكم — {{ auth()->user()->name }}
                    </h1>
                    <p class="text-sm text-brand-teal-100/85 mt-1">
                        يمكنك الانتقال مباشرة إلى قائمة المتسابقين الحاضرين للبدء في التقييم الإلكتروني
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('student.present_index') }}" class="btn-brand-gold">
                        <x-svgicon.brand-icon name="present" class="w-5 h-5" />
                        <span>المتسابقون الحاضرون</span>
                    </a>
                    <a href="{{ route('program.guide') }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                        <x-svgicon.brand-icon name="guide" class="w-4 h-4 text-brand-gold-300" />
                        <span>دليل المحكم (PDF)</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Quick Steps for Judge --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-brand p-5">
                <div class="w-11 h-11 rounded-xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center mb-3">
                    <x-svgicon.brand-icon name="present" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-brand-teal-900 mb-1">1. استعراض المتسابقين</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    انتقل إلى صفحة «المتسابقون الحاضرون» لمتابعة المتسابق الحالي الذي يختار رئيس اللجنة باقته.
                </p>
            </div>
            <div class="card-brand p-5">
                <div class="w-11 h-11 rounded-xl bg-brand-gold-50 border border-brand-gold-200 text-brand-gold-700 flex items-center justify-center mb-3">
                    <x-svgicon.brand-icon name="quran" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-brand-teal-900 mb-1">2. التقييم المباشر</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    استعرض الآيات القرآنية بخط المصحف وسجل الدرجات بدقة باستخدام أزرار التعديل السريع (+ / -).
                </p>
            </div>
            <div class="card-brand p-5">
                <div class="w-11 h-11 rounded-xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center mb-3">
                    <x-svgicon.brand-icon name="results" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-brand-teal-900 mb-1">3. اعتماد النتيجة</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    بعد إتمام جميع الأسئلة، يتم مراجعة الدرجات النهائية واعتماد نتيجة المتسابق في النظام.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
