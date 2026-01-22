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

        <div class="no-print mb-6 flex flex-wrap items-center gap-4">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                طباعة
            </button>

            <form method="GET" action="{{ route('finished_student_list') }}" class="flex gap-3">
                <select name="gender" class="border rounded px-3 py-2">
                    <option value="">-- الجنس --</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>

                <select name="level" class="border rounded px-3 py-2">
                    <option value="">-- المستوى --</option>
                    <option value="memorize" {{ request('level') === 'memorize' ? 'selected' : '' }}>
                        {{ __('memorize') }}
                    </option>
                    <option value="memorize_with_tafseer" {{ request('level') === 'memorize_with_tafseer' ? 'selected' : '' }}>
                        {{ __('memorize_with_tafseer') }}
                    </option>
                </select>

                <button type="submit" class="bg-gray-700 text-white px-5 py-2 rounded hover:bg-gray-800">
                    تصفية
                </button>
            </form>
        </div>

        <div class="bg-white overflow-x-auto mb-6">
            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-3 text-right">#</th>
                        <th class="border px-4 py-3 text-right">اسم المتسابق</th>
                        <th class="border px-4 py-3 text-right">الجنس</th>
                        <th class="border px-4 py-3 text-right">المستوى</th>
                        <th class="border px-4 py-3 text-right">الباقة</th>
                        <th class="border px-4 py-3 text-center">الحفظ</th>
                        <th class="border px-4 py-3 text-center">التفسير</th>
                        <th class="border px-4 py-3 text-center">المجموع</th>
                        <th class="border px-4 py-3 text-center bg-gray-200">النسبة %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($competitions as $index => $comp)
                        @php
                            $percentage = '-';
                            if(is_numeric($comp->final_score)) {
                                if ($comp->level === 'memorize_with_tafseer') {
                                    // Equation: (Score / 140) * 100
                                    $calc = ($comp->final_score / 140) * 100;
                                    $percentage = number_format($calc, 2) . '%';
                                } else {
                                    // Standard level (assumes score is out of 100)
                                    $percentage = number_format($comp->final_score, 2) . '%';
                                }
                            }
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 text-right">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2 text-right">
                                {{ $comp->student->name ?? '-' }}
                                <p class="text-gray-400 text-xs">{{ $comp->student->national_id ?? '-' }}</p>
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $comp->student->gender ? __($comp->student->gender) : '-' }}
                            </td>
                            <td class="border px-4 py-2 text-right">{{ __($comp->level) }}</td>
                            <td class="border px-4 py-2 text-right">{{ $comp->questionset->title ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center">{{ $comp->memorization_score ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center">{{ $comp->tafseer_score ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center font-bold">{{ $comp->final_score ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center font-bold text-blue-700 bg-gray-50" dir="ltr">
                                {{ $percentage }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border px-4 py-6 text-center text-gray-500">
                                لا توجد نتائج مطابقة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded border border-gray-200">
            <h3 class="font-bold mb-2 text-gray-800">ℹ️ آلية احتساب النسبة المئوية:</h3>
            <ul class="list-disc list-inside space-y-1">
                <li>
                    <span class="font-semibold text-gray-800">مستوى (حفظ مع تفسير):</span>
                    يتم احتساب النسبة من المجموع الكلي (140 درجة) وفق المعادلة:
                    <span dir="ltr" class="font-mono text-xs bg-gray-200 px-2 py-0.5 rounded mx-1 text-black">
                        (المجموع ÷ 140) × 100
                    </span>
                </li>
                <li>
                    <span class="font-semibold text-gray-800">باقي المستويات:</span>
                    تعتمد درجة المجموع النهائية كما هي (من 100).
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
                font-size: 12px;
            }

            @page {
                margin: 2cm;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>