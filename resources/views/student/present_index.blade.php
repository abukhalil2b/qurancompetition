<x-app-layout>
    <div class="space-y-6 sm:p-8" dir="rtl">

        @php
            $isLeader = auth()->user()->isCommitteeLeader($committee->id);

            $genderLabels = [
                'male'   => 'ذكر',
                'female' => 'أنثى',
            ];

            $statusStyles = [
                'with_committee'     => 'bg-red-50 text-red-700 border border-red-100',
                'present'            => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
                'finish_competition' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            ];

            $statusLabels = [
                'with_committee'     => 'مع اللجنة',
                'present'            => 'حاضر',
                'finish_competition' => 'أنهى المسابقة',
            ];
        @endphp

        {{-- Committee info card --}}
        <div class="card-brand overflow-hidden">
            <div class="px-6 py-4 bg-islamic-pattern-dark text-white border-b-2 border-brand-gold-400 flex items-center justify-between">
                <h2 class="text-lg font-bold flex items-center gap-2.5">
                    <x-svgicon.brand-icon name="committees" class="w-5 h-5 text-brand-gold-300" />
                    <span>معلومات اللجنة</span>
                </h2>
                <x-svgicon.rosette class="w-6 h-6" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-brand-sand-200">
                <div class="p-5 flex items-start gap-4 hover:bg-brand-teal-50/40 transition">
                    <div class="p-3 bg-brand-teal-50 border border-brand-teal-200 rounded-xl text-brand-teal-700">
                        <x-svgicon.brand-icon name="centers" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs text-brand-teal-700/80 font-semibold">المركز</p>
                        <p class="text-base font-bold text-brand-teal-950 mt-0.5">{{ $center->title }}</p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4 hover:bg-brand-teal-50/40 transition">
                    <div class="p-3 bg-brand-gold-50 border border-brand-gold-200 rounded-xl text-brand-gold-700">
                        <x-svgicon.brand-icon name="stages" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs text-brand-teal-700/80 font-semibold">المرحلة</p>
                        <p class="text-base font-bold text-brand-teal-950 mt-0.5">{{ $stage->title }}</p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4 hover:bg-brand-teal-50/40 transition">
                    <div class="p-3 bg-brand-teal-50 border border-brand-teal-200 rounded-xl text-brand-teal-700">
                        <x-svgicon.brand-icon name="committees" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs text-brand-teal-700/80 font-semibold">اللجنة</p>
                        <p class="text-base font-bold text-brand-teal-950 mt-0.5">{{ $committee->title }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Non-leader notice --}}
        @unless ($isLeader)
            @php
                $leader = $committee->leader?->user;
            @endphp

            <div class="bg-amber-50 border-r-4 border-amber-400 rounded-lg p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 mt-1">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-6.18 10.7A2 2 0 005.82 18h12.36a2 2 0 001.71-3.44l-6.18-10.7a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-amber-800">تنبيه للجان التحكيم</h3>
                        <p class="text-amber-700 mt-1 leading-relaxed">
                            قائمة الحضور تظهر فقط لرئيس اللجنة
                            @if($leader)
                                (<span class="font-bold underline">{{ $leader->name }}</span>).
                            @endif
                            <br class="hidden sm:block">
                            يرجى تحديث الصفحة يدوياً عند إبلاغكم باختيار الطالب للباقة.
                        </p>
                    </div>
                </div>

                <button onclick="window.location.reload()"
                    class="whitespace-nowrap flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-amber-200 text-amber-700 font-semibold rounded-lg shadow-sm hover:bg-amber-100 focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    تحديث القائمة
                </button>
            </div>
        @endunless

        {{-- Competitions table --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">قائمة المتسابقين ({{ $competitions->count() }})</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">
                    <thead class="bg-gray-50 text-gray-500 text-sm font-medium tracking-wider">
                        <tr>
                            <th class="px-6 py-3 border-b">#</th>
                            <th class="px-6 py-3 border-b w-1/3">بيانات المتسابق</th>
                            <th class="px-6 py-3 border-b text-center">المستوى</th>
                            <th class="px-6 py-3 border-b text-center">الحالة</th>
                            <th class="px-6 py-3 border-b text-center">وقت التسجيل</th>
                            <th class="px-6 py-3 border-b text-center">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($competitions as $competition)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-500 font-mono text-sm">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-base font-bold text-gray-900 mb-1">
                                            {{ $competition->student->name ?? 'غير معروف' }}
                                        </span>
                                        <div class="text-xs text-gray-500 flex flex-wrap gap-x-3 gap-y-1">
                                            <span>
                                                <span class="font-medium text-gray-400">النوع:</span>
                                                {{ $genderLabels[$competition->student->gender] ?? '—' }}
                                            </span>
                                            <span class="text-gray-300">|</span>
                                            <span>
                                                <span class="font-medium text-gray-400">الرقم المدني:</span>
                                                {{ $competition->student->national_id ?? '—' }}
                                            </span>
                                            @if (!empty($competition->student->nationality))
                                                <span class="text-gray-300">|</span>
                                                <span>
                                                    <span class="font-medium text-gray-400">الجنسية:</span>
                                                    {{ $competition->student->nationality }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $competition->level }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                 {{ $statusStyles[$competition->student_status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$competition->student_status] ?? $competition->student_status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm text-gray-500 font-mono" dir="ltr">
                                    {{ $competition->present_at?->format('H:i') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if ($competition->questionset)
                                        <a href="{{ route('student.choose_questionset', $competition->id) }}"
                                           class="inline-flex items-center justify-center gap-1 w-full max-w-[140px] px-3 py-1.5
                                                  bg-white border border-gray-300 text-gray-700 hover:bg-gray-50
                                                  text-xs font-bold rounded-lg transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            عرض الأسئلة
                                        </a>
                                    @elseif ($isLeader)
                                        <a href="{{ route('student.choose_questionset', $competition->id) }}"
                                           class="inline-flex items-center justify-center gap-1 w-full max-w-[140px] px-3 py-1.5
                                                  bg-purple-600 text-white hover:bg-purple-700 shadow-sm
                                                  text-xs font-bold rounded-lg transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            اختيار باقة
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">
                                            لم يتم الاختيار
                                        </span>
                                    @endif

                                    @if (auth()->user()->user_type == 'admin')
                                    
                                    <a href="{{ route('result.show', $competition->id) }}"
                                           class="mt-1 inline-flex items-center justify-center gap-1 w-full max-w-[140px] px-3 py-1.5
                                                  bg-white border border-gray-300 text-gray-700 hover:bg-gray-50
                                                  text-xs font-bold rounded-lg transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            عرض النتيجة
                                        </a>
                                        @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 p-4 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm font-medium">لا توجد سجلات حضور حالياً</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>