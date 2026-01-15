<x-app-layout>
    <div class="container mx-auto py-8 px-4" dir="rtl">

        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2">
                قائمة المتسابقين الذين أتموا المسابقة وتم اعتماد نتائجهم
            </h1>
            <p class="text-gray-600">
                التاريخ: {{ now()->format('Y/m/d') }}
            </p>
        </div>

        <!-- Actions (Hidden in Print) -->
        <div class="no-print mb-6 flex flex-wrap items-center gap-4">

            <!-- Print Button -->
            <button
                onclick="window.print()"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
            >
                طباعة
            </button>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('finished_student_list') }}" class="flex gap-3">
                <select name="gender" class="border rounded px-3 py-2">
                    <option value="">-- الجنس --</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>
                        ذكر
                    </option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>
                        أنثى
                    </option>
                </select>

                <select name="level" class="border rounded px-3 py-2">
                    <option value="">-- المستوى --</option>
                    <option value="memorize" {{ request('level') === 'memorize' ? 'selected' : '' }}>
                        {{ __('memorize') }}
                    </option>
                    <option value="memorize_with_tafseer"
                        {{ request('level') === 'memorize_with_tafseer' ? 'selected' : '' }}>
                        {{ __('memorize_with_tafseer') }}
                    </option>
                </select>

                <button
                    type="submit"
                    class="bg-gray-700 text-white px-5 py-2 rounded hover:bg-gray-800"
                >
                    تصفية
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white overflow-x-auto">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($competitions as $index => $comp)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 text-right">
                                {{ $index + 1 }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $comp->student->name ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $comp->student->gender ? __($comp->student->gender) : '-' }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ __($comp->level) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $comp->questionset->title ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center">
                                {{ $comp->memorization_score ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center">
                                {{ $comp->tafseer_score ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center font-bold">
                                {{ $comp->final_score ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border px-4 py-6 text-center text-gray-500">
                                لا توجد نتائج مطابقة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Styles -->
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
