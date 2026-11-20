<x-app-layout>
    <div class="space-y-6">

        {{-- Architectural Hero Banner --}}
        <div class="page-header-brand bg-islamic-pattern-dark">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold-400/20 border border-brand-gold-400/40 text-brand-gold-200 text-xs font-bold mb-3">
                        <x-svgicon.rosette class="w-4 h-4" />
                        <span>المرحلة النشطة حالياً: {{ $activeStage->title }}</span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold text-white">
                        مرحباً بك، {{ auth()->user()->name }}
                    </h1>
                    <p class="text-sm text-brand-teal-100/85 mt-1">
                        لوحة التحكم الرئيسية لمسابقة مؤسسة الشيخ مستهيل بن أحمد بن علي المعشني الوقفية الخاصة للقرآن الكريم وقراءاته
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('program.guide') }}" target="_blank" class="btn-brand-gold">
                        <x-svgicon.brand-icon name="guide" class="w-4 h-4" />
                        <span>دليل البرنامج (PDF)</span>
                    </a>
                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('statistic_dashboard') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                            <x-svgicon.brand-icon name="statistics" class="w-4 h-4 text-brand-gold-300" />
                            <span>الإحصائيات العامة</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Navigation Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if (auth()->user()->user_type === 'admin')
                <a href="{{ route('stage.index') }}" class="card-brand p-5 hover:border-brand-teal-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center group-hover:bg-brand-teal-700 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="stages" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">التصفيات والمراحل</h3>
                            <p class="text-xs text-gray-500 mt-0.5">إدارة وتفعيل مراحل المسابقة</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('center.index') }}" class="card-brand p-5 hover:border-brand-teal-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center group-hover:bg-brand-teal-700 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="centers" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">المراكز</h3>
                            <p class="text-xs text-gray-500 mt-0.5">استعراض المراكز وتقاريرها الإحصائية</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('committee.index') }}" class="card-brand p-5 hover:border-brand-teal-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-gold-50 border border-brand-gold-200 text-brand-gold-700 flex items-center justify-center group-hover:bg-brand-gold-500 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="committees" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">اللجان والتحكيم</h3>
                            <p class="text-xs text-gray-500 mt-0.5">توزيع المحكمين وتحديد رؤساء اللجان</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('student.index') }}" class="card-brand p-5 hover:border-brand-teal-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center group-hover:bg-brand-teal-700 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="students" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">المتسابقون</h3>
                            <p class="text-xs text-gray-500 mt-0.5">إدارة بيانات المتسابقين وتسجيلهم</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('finished_student_list') }}" class="card-brand p-5 hover:border-brand-gold-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-gold-50 border border-brand-gold-200 text-brand-gold-700 flex items-center justify-center group-hover:bg-brand-gold-500 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="results" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">النتائج النهائية</h3>
                            <p class="text-xs text-gray-500 mt-0.5">استعراض وطباعة وتصدير النتائج المعتمدة</p>
                        </div>
                    </div>
                </a>
            @endif

            @if (in_array(auth()->user()->user_type, ['question_admin', 'admin']))
                <a href="{{ route('questionset.index', 1) }}" class="card-brand p-5 hover:border-brand-teal-400 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center group-hover:bg-brand-teal-700 group-hover:text-white transition">
                            <x-svgicon.brand-icon name="questions" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-teal-900 text-base">باقات الأسئلة</h3>
                            <p class="text-xs text-gray-500 mt-0.5">إعداد وإدارة باقات المستوى الأول والثاني</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
