<x-app-layout>
    <div class="space-y-6" dir="rtl">
        {{-- Architectural Header --}}
        <div class="page-header-brand bg-islamic-pattern-dark">
            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-brand-gold-400/20 border border-brand-gold-400/40 flex items-center justify-center text-brand-gold-300">
                        <x-svgicon.brand-icon name="centers" class="w-6 h-6" />
                    </div>
                    <div>
                        <h1 class="font-display text-2xl font-bold text-white">المراكز المعتمدة — {{ $stage->title }}</h1>
                        <p class="text-xs text-brand-teal-100/80 mt-0.5">استعراض قوائم المتسابقين والتقارير الإحصائية لكل مركز</p>
                    </div>
                </div>
                <x-svgicon.rosette class="w-8 h-8 hidden sm:block" />
            </div>
        </div>

        <div class="card-brand overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr>
                            <th class="px-5 py-3.5 w-16 text-xs">#</th>
                            <th class="px-5 py-3.5 text-sm">اسم المركز</th>
                            <th class="px-5 py-3.5 w-72 text-sm">اللجان والإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-sand-100 text-sm">
                        @forelse ($centers as $center)
                            <tr class="transition-colors">
                                <td class="px-5 py-4 text-brand-teal-700 font-bold">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4 font-bold text-brand-teal-950">
                                    {{ $center->title }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-2.5">
                                        <span class="badge-gold w-fit">
                                            عدد اللجان: {{ $center->committees_count }}
                                        </span>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('center.show', $center->id) }}"
                                                class="btn-brand-primary py-1.5 px-3 text-xs">
                                                <x-svgicon.brand-icon name="students" class="w-4 h-4" />
                                                <span>قائمة المتسابقين</span>
                                            </a>

                                            <a href="{{ route('center.statistic', $center->id) }}"
                                                class="btn-brand-outline py-1.5 px-3 text-xs">
                                                <x-svgicon.brand-icon name="statistics" class="w-4 h-4 text-brand-gold-600" />
                                                <span>التقرير الإحصائي</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-gray-500 text-sm">
                                    لا توجد مراكز مسجلة حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
