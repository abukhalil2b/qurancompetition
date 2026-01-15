<x-app-layout>
    <div class="container mx-auto py-8 px-4" dir="rtl">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2">قائمة المتسابقين الذين أتموا المسابقة وتم اعتماد نتائجهم</h1>
            <p class="text-gray-600">التاريخ: {{ date('Y/m/d') }}</p>
        </div>

        <!-- Print Button -->
        <div class="mb-4 print:hidden">
            <button onclick="window.print()" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                طباعة
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 py-3 px-4 text-right">#</th>
                        <th class="border border-gray-300 py-3 px-4 text-right">اسم المتسابق</th>
                        <th class="border border-gray-300 py-3 px-4 text-right">المستوى</th>
                        <th class="border border-gray-300 py-3 px-4 text-right">الباقة</th>
                        <th class="border border-gray-300 py-3 px-4 text-center">الحفظ</th>
                        <th class="border border-gray-300 py-3 px-4 text-center">التفسير</th>
                        <th class="border border-gray-300 py-3 px-4 text-center">المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($competitions as $index => $comp)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4 text-right">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 py-2 px-4 text-right">
                                {{ $comp->student->name ?? 'N/A' }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-right">
                                {{ $comp->level == 'memorize_with_tafseer' ? 'حفظ مع تفسير' : 'حفظ' }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-right">
                                {{ $comp->questionset->title ?? '-' }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-center">
                                {{ $comp->memorization_score ?? '-' }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-center">
                                {{ $comp->tafseer_score ?? '-' }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-center font-bold">
                                {{ $comp->final_score ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }
            
            @page {
                margin: 2cm;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>