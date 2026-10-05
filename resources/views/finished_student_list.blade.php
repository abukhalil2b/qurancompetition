<x-app-layout>
    <div class="container mx-auto py-8 px-4" dir="rtl">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2">
                قائمة المتسابقين الذين أتموا المسابقة وتم اعتماد نتائجهم
            </h1>
            <p class="text-gray-600">
                التاريخ: {{ now()->format('Y/m/d') }}
            </p>
        </div>

        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    طباعة
                </button>

                <a href="{{ route('finished_student_list', array_merge(request()->all(), ['export' => 'true'])) }}"
                    target="_blank"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    تصدير إكسل
                </a>
            </div>

            <form method="GET" action="{{ route('finished_student_list') }}" class="flex gap-3">
    {{-- Center Filter --}}
    <select name="center_id" class="border rounded px-3 py-2 bg-white text-sm">
        <option value="">-- كل المراكز --</option>
        @foreach($centers as $center)
            <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>
                {{ $center->title }}
            </option>
        @endforeach
    </select>

    {{-- Gender Filter --}}
    <select name="gender" class="border rounded px-3 py-2 bg-white text-sm">
        <option value="">-- كل الأجناس --</option>
        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
    </select>

    {{-- Level Filter --}}
    <select name="level" class="border rounded px-3 py-2 bg-white text-sm">
        <option value="">-- كل المستويات --</option>
        <option value="1" {{ request('level') === '1' ? 'selected' : '' }}>المستوى الأول</option>
        <option value="2" {{ request('level') === '2' ? 'selected' : '' }}>المستوى الثاني</option>
    </select>

    <button type="submit" class="bg-gray-700 text-white px-5 py-2 rounded hover:bg-gray-800 transition text-sm">
        تصفية
    </button>
</form>
        </div>

        <div class="bg-white overflow-x-auto mb-6 rounded-lg shadow-sm border">
            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-4 py-3 text-right w-12">#</th>
                        <th class="border px-4 py-3 text-right">اسم المتسابق</th>
                        <th class="border px-4 py-3 text-right w-20">الجنس</th>
                        <th class="border px-4 py-3 text-right w-32">المستوى</th>
                        <th class="border px-4 py-3 text-right w-32">المركز</th>
                        <th class="border px-4 py-3 text-right">الباقة</th>
                        <th class="border px-4 py-3 text-center w-28">المجموع</th>
                        <th class="border px-4 py-3 text-center w-28 bg-gray-200">النسبة %</th>
                        <th class="border px-4 py-3 text-center w-24 no-print">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($competitions as $index => $comp)
                        @php
                            $percentage = '-';
                            if (is_numeric($comp->final_score)) {
                                $maxScore = ((int) $comp->level === 1) ? 120 : 100;
                                $percentage = number_format(($comp->final_score / $maxScore) * 100, 2) . '%';
                            }
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 text-right font-medium">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2 text-right">
                                <div class="font-semibold text-gray-800">{{ $comp->student->name ?? '-' }}</div>
                                @if (!empty($comp->student->national_id))
                                    <div class="text-gray-400 text-xs">{{ $comp->student->national_id }}</div>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ ($comp->student->gender ?? '') === 'male' ? 'ذكر' : (($comp->student->gender ?? '') === 'female' ? 'أنثى' : '-') }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ (int) $comp->level === 1 ? 'المستوى الأول' : 'المستوى الثاني' }}
                            </td>
                            <td class="border px-4 py-2 text-right">{{ $comp->center->title ?? '-' }}</td>
                            <td class="border px-4 py-2 text-right">{{ $comp->questionset->title ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center font-bold text-gray-900">
                                {{ is_numeric($comp->final_score) ? number_format($comp->final_score, 2) : '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center font-bold text-blue-700 bg-gray-50" dir="ltr">
                                {{ $percentage }}
                            </td>
                            <td class="border px-4 py-2 text-center no-print">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('result.show', $comp->id) }}"
                                        class="text-blue-600 hover:text-blue-800 transition" title="عرض الشهادة/النتيجة">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('result.detailed', $comp->id) }}"
                                        class="text-indigo-600 hover:text-indigo-800 transition" title="تفاصيل درجات المحكمين">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border px-4 py-8 text-center text-gray-500">
                                لا توجد نتائج مطابقة للشروط الحالية
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="font-bold mb-2 text-gray-800">ℹ️ آلية احتساب النسبة المئوية:</h3>
            <ul class="list-disc list-inside space-y-1">
                <li>
                    <span class="font-semibold text-gray-800">المستوى الأول:</span>
                    يتم احتساب النسبة من المجموع الكلي (120 درجة) وفق المعادلة:
                    <span dir="ltr" class="font-mono text-xs bg-gray-200 px-2 py-0.5 rounded mx-1 text-black">
                        (المجموع ÷ 120) × 100
                    </span>
                </li>
                <li>
                    <span class="font-semibold text-gray-800">المستوى الثاني:</span>
                    يتم احتساب النسبة من المجموع الكلي (100 درجة) وفق المعادلة:
                    <span dir="ltr" class="font-mono text-xs bg-gray-200 px-2 py-0.5 rounded mx-1 text-black">
                        (المجموع ÷ 100) × 100
                    </span>
                </li>
            </ul>
        </div>

    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            table {
                font-size: 11px;
            }

            @page {
                margin: 1.5cm;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>