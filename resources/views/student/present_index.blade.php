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
                    </tr>
                </thead>

                <tbody>
                    @forelse ($competitions as $item)
                        @php
                            $statusClasses = [
                                'with_committee' => 'bg-red-700 text-white',
                                'present' => 'bg-yellow-500 text-white',
                                'finish_competition' => 'bg-green-600 text-white',
                            ];
                        @endphp

                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $item->id }}</td>
                            <td class="px-4 py-2 border text-right text-sm space-y-1">
                                <div><strong>الاسم:</strong> {{ $item->student->name ?? '-' }}</div>
                                <div><strong>النوع:</strong> {{ __($item->student->gender ?? '-') }}</div>
                                <div><strong>الرقم المدني:</strong> {{ $item->student->national_id ?? '-' }}</div>
                                <div><strong>الجنسية:</strong> {{ $item->student->nationality ?? '-' }}</div>
                            </td>


                            <td class="px-4 py-2 border">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$item->student_status] ?? 'bg-gray-300' }}">
                                    {{ __($item->student_status) }}
                                </span>
                            </td>

                            <td class="px-4 py-2 border">
                                {{ $item->created_at->format('H:i') }}
                            </td>

                            <td class="px-4 py-2 border">{{ $item->student->level }}</td>
                            <td class="px-4 py-4 text-center space-y-2">
                                @if ($item->questionset)
                                    <div class="text-xs font-bold text-green-600">
                                        ✓ {{ $item->questionset->title }}
                                    </div>
                                @else
                                    <div class="text-xs text-gray-400">
                                        لم يتم اختيار باقة
                                    </div>
                                @endif

                                @if ($committeeUser->role === 'judge')
                                    <a href="{{ route('student.choose_questionset', $item->id) }}"
                                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold py-1.5 px-4 rounded shadow-sm transition">
                                        {{ $item->questionset ? 'عرض أسئلة الباقة' : 'اختيار باقة' }}
                                    </a>
                                @endif
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
