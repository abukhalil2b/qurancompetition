<x-app-layout>
    <div class="space-y-6">
        <div class="page-header-brand bg-islamic-pattern-dark">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold-400/20 border border-brand-gold-400/40 text-brand-gold-200 text-xs font-bold mb-3">
                        <x-svgicon.rosette class="w-4 h-4" />
                        <span>{{ $activeStage->title }}</span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-bold text-white">
                        لوحة اللجنة — {{ auth()->user()->name }}
                    </h1>
                    <p class="text-sm text-brand-teal-100/85 mt-1">
                        متابعة أعمال اللجنة والمشاركين في المسابقة القرآنية
                    </p>
                </div>
                <a href="{{ route('program.guide') }}" target="_blank" class="btn-brand-gold">
                    <x-svgicon.brand-icon name="guide" class="w-4 h-4" />
                    <span>دليل البرنامج (PDF)</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
