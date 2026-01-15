<x-app-layout>
    <div class="space-y-6 p-4 sm:p-8" dir="rtl">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    معلومات اللجنة
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-gray-100">
                <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">المركز</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">{{ $center->title }}</p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">المرحلة</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">{{ $stage->title }}</p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">اللجنة</p>
                        <p class="text-base font-bold text-gray-900 mt-0.5">{{ $committee->title }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if (!auth()->user()->isCommitteeLeader($stage->id))
            @php
                $leader = $committee->leaderForStage($stage->id)->with('user')->first()?->user;
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
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">قائمة المتسابقين ({{ count($competitions) }})</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-right">
                    <thead class="bg-gray-50 text-gray-500 text-sm font-medium uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 border-b">#</th>
                            <th class="px-6 py-3 border-b w-1/3">بيانات المتسابق</th>
                            <th class="px-6 py-3 border-b text-center">المستوى</th>
                            <th class="px-6 py-3 border-b text-center">الحالة</th>
                            <th class="px-6 py-3 border-b text-center">وقت الحضور</th>
                            <th class="px-6 py-3 border-b text-center">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($competitions as $item)
                            @php
                                $statusStyles = [
                                    'with_committee' => 'bg-red-50 text-red-700 border border-red-100',
                                    'present' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
                                    'finish_competition' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                ];
                                $statusLabel = [
                                    'with_committee' => 'مع اللجنة',
                                    'present' => 'حاضر',
                                    'finish_competition' => 'أنهى المسابقة',
                                ]; // Or use translation key
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-500 font-mono text-sm">
                                    {{ $item->id }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-base font-bold text-gray-900 mb-1">
                                            {{ $item->student->name ?? 'غير معروف' }}
                                        </span>
                                        <div class="text-xs text-gray-500 flex flex-wrap gap-x-3 gap-y-1">
                                            <span><span class="font-medium text-gray-400">النوع:</span> {{ __($item->student->gender ?? '-') }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span><span class="font-medium text-gray-400">الرقم المدني:</span> {{ $item->student->national_id ?? '-' }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span><span class="font-medium text-gray-400">الجنسية:</span> {{ $item->student->nationality ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $item->student->level }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusStyles[$item->student_status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ __($item->student_status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm text-gray-500 font-mono" dir="ltr">
                                    {{ $item->created_at->format('H:i') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        @if ($item->questionset)
                                            <div class="flex items-center gap-1 text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-xs font-bold">{{ $item->questionset->title }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">لم يتم الاختيار</span>
                                        @endif

                                        @if ($committeeUser->role === 'judge')
                                            <a href="{{ route('student.choose_questionset', $item->id) }}"
                                               class="inline-flex items-center justify-center gap-1 w-full max-w-[140px] px-3 py-1.5 
                                                      {{ $item->questionset ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-purple-600 text-white hover:bg-purple-700 shadow-sm' }} 
                                                      text-xs font-bold rounded-lg transition-all duration-200">
                                                @if($item->questionset)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    عرض الأسئلة
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    اختيار باقة
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 p-4 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
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