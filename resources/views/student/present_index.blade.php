<x-app-layout>
        <div class="space-y-6 p-3">

            {{-- Header Information --}}
            <div class="bg-white border rounded-lg p-4 shadow-sm">
                <h2 class="text-lg font-bold mb-3">معلومات اللجنة</h2>

                <div class="flex gap-4 text-sm">
                    <div class="p-3 border rounded bg-gray-50">
                        <span class="font-semibold">المركز:</span>
                        <span>{{ $center->title }}</span>
                    </div>

                    <div class="p-3 border rounded bg-gray-50">
                        <span class="font-semibold">المرحلة:</span>
                        <span>{{ $stage->title }}</span>
                    </div>

                    <div class="p-3 border rounded bg-gray-50">
                        <span class="font-semibold">اللجنة:</span>
                        <span>{{ $committee->title }}</span>
                    </div>
                </div>
            </div>


            {{-- Students Table --}}
            <div class="bg-white border rounded-lg shadow-sm overflow-scroll">
                <table class="min-w-full text-center">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">اسم المتسابق</th>
                            <th class="px-4 py-2 border">الحالة</th>
                            <th class="px-4 py-2 border">وقت الحضور</th>
                            <th class="px-4 py-2 border">المستوى</th>
                            <th class="px-4 py-2 border">اختيار باقة</th>
                            <th class="px-4 py-2 border">النتيجة النهائية</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($competitions as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $item->id }}</td>
                                <td class="px-4 py-2 border">
                                    <div>الاسم: {{ $item->student_name }}</div>
                                    <div>النوع: {{ __($item->gender) }}</div>
                                    <div>الرقم المدني: {{ $item->national_id }}</div>
                                    <div>الجنسية: {{ $item->nationality }}</div>
                                </td>

                                <td class="px-4 py-2 border {{ $item->student_status == 'with_committee' ? 'bg-red-800 text-white':'' }} ">{{ __($item->student_status) }}</td>
                                <td class="px-4 py-2 border">{{ $item->present_at }}</td>
                                <td class="px-4 py-2 border">{{ $item->level }}</td>
                                <td class="px-4 py-2 border">
                                    <a class="bg-purple-100 border border-purple-700 text-purple-700 rounded px-3"
                                        href="{{ route('student.choose_questionset', $item->id) }}">الباقات</a>
                                </td>
                                <td class="px-4 py-2 border">
                                    <div class="px-4 py-2 border"> المجموع {{ $item->grand_total }}</div>
                                    <div class="px-4 py-2 border"> عدد المحكمين {{ $item->judge_count }}</div>
                                    <div class="px-4 py-2 border"> المتوسط
                                        {{ $item->judge_count > 0 ? $item->grand_total / $item->judge_count : 0 }}</div>
                                    <div class="px-4 py-2 border">
                                        @if ($item->questionset)
                                            <div>{{ $item->questionset->title }}</div>
                                            <div>عدد الأسئلة {{ $item->questionset->questions->count() }}</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-gray-500">
                                    لا توجد سجلات حضور حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
</x-app-layout>
