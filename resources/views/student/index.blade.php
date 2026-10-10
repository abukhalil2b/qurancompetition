<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        إدارة المتسابقين
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        عرض بيانات المتسابقين ومستوياتهم وبيانات التواصل.
                    </p>
                </div>

                <a href="{{ route('student.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إضافة متسابق
                </a>
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">إجمالي المتسابقين</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ $students->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.03M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.03M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">المستوى الأول</p>
                            <p class="mt-2 text-3xl font-bold text-blue-700">
                                {{ $students->where('level', 1)->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">المستوى الثاني</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-700">
                                {{ $students->where('level', 2)->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Students Table --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-900">قائمة المتسابقين</h2>
                        <p class="text-xs text-gray-500 mt-1">
                            بيانات المسجلين في النظام
                        </p>
                    </div>

                    <span class="px-3 py-1.5 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg">
                        {{ $students->count() }} متسابق
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">

                        <thead class="bg-gray-50">
                            <tr class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-4">#</th>
                                <th class="px-5 py-4">المتسابق</th>
                                <th class="px-5 py-4">الجنس</th>
                                <th class="px-5 py-4">الجنسية</th>
                                <th class="px-5 py-4">المستوى</th>
                                <th class="px-5 py-4">البيانات الإضافية</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($students as $student)
                                <tr class="hover:bg-gray-50/80 transition-colors">

                                    <td class="px-5 py-4 text-sm text-gray-400 font-medium">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- Student --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl
                                                {{ $student->gender === 'male' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.8" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $student->name }}
                                                </p>
                                                <a class="text-xs text-gray-400 mt-1" href="{{ route('student.edit',$student->id) }}">تعديل</a>
                                               
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Gender --}}
                                    <td class="px-5 py-4">
                                        @if ($student->gender === 'male')
                                            <span class="inline-flex px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                                                ذكر
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-lg bg-pink-50 text-pink-700 text-xs font-semibold">
                                                أنثى
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Nationality --}}
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $student->nationality ?: '-' }}
                                    </td>

                                    {{-- Level --}}
                                    <td class="px-5 py-4">
                                        @if ($student->level == 1)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                المستوى الأول
                                            </span>
                                        @elseif ($student->level == 2)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                المستوى الثاني
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Contact Details --}}
                                    <td class="px-5 py-4">
                                        <div class="space-y-2 text-xs text-gray-500">

                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400 shrink-0"
                                                     fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.684l1.5 4.5a1 1 0 01-.502 1.21l-2.1 1.05a11.04 11.04 0 005.42 5.42l1.05-2.1a1 1 0 011.21-.502l4.5 1.5a1 1 0 01.684.95V19a2 2 0 01-2 2h-1C9.163 21 3 14.837 3 7V5z"/>
                                                </svg>
                                                <span dir="ltr">{{ $student->phone ?: 'لا يوجد هاتف' }}</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400 shrink-0"
                                                     fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span>
                                                    {{ $student->national_id ?: 'لا يوجد رقم مدني' }}
                                                </span>
                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.196-2.03M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.03M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-gray-800">
                                                لا يوجد متسابقون حتى الآن
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                ابدأ بإضافة أول متسابق إلى النظام.
                                            </p>
                                            <a href="{{ route('student.create') }}"
                                               class="mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                                إضافة متسابق جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
