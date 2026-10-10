<x-app-layout>
    <div x-data="{ filter: '' }" class="p-6">

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">
                متسابقو اللجنة
            </h1>
            <p class="text-sm text-gray-500">
                {{ $committee->title ?? '' }}
                @if (!empty($center))
                    • {{ $center->title }}
                @endif
                @if (!empty($stage))
                    • {{ $stage->title }}
                @endif
            </p>
        </div>
        <div class="mb-4 flex gap-2">
            المحكمون:
            @foreach ($judges as $judge)
                <div>{{ $judge->user->name }}• </div>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="mb-4 flex gap-2">
            <label for="student-search" class="block mb-2 font-semibold text-gray-700">
                البحث عن متسابق
            </label>

            <input type="text" id="student-search" placeholder="ابحث بالاسم، الرقم المدني، أو رقم الهاتف..."
                class="w-full md:w-1/2 lg:w-1/3 rounded-lg border-gray-300 shadow-sm
                       focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                x-model="filter" dir="rtl">
        </div>

        {{-- Students table --}}
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
            <table class="min-w-full divide-y divide-gray-200" dir="rtl">

                <thead class="bg-gray-100">
                    <tr class="text-right text-gray-700 text-sm">
                        <th class="px-4 py-3 rounded-tr-xl">#</th>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">المستوى</th>
                        <th class="px-4 py-3">اللجنة</th>
                        <th class="px-4 py-3">الحالة</th>
                        <th class="px-6 py-3 border-b text-center">وقت الحضور</th>
                        <th class="px-4 py-3 rounded-tl-xl">الإجراءات</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach ($competitions as $competition)
                        @php
                            $student = $competition->student;

                            $statusClasses = [
                                'registration' => 'bg-blue-100 text-blue-700',
                                'present' => 'bg-emerald-100 text-emerald-700',
                                'with_committee' => 'bg-amber-100 text-amber-700',
                                'withdraw' => 'bg-red-100 text-red-700',
                                'waiting_finalization' => 'bg-purple-100 text-purple-700',
                                'finish_competition' => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp

                        <tr class="text-right hover:bg-gray-50 transition-colors duration-200" x-data="{
                            searchStr: @js(mb_strtolower(($student->name ?? '') . ' ' . ($student->national_id ?? '') . ' ' . ($student->phone ?? '')))
                        }"
                            x-show="filter === '' || searchStr.includes(filter.toLowerCase())">

                            {{-- # --}}
                            <td class="px-4 py-3">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Name --}}
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $student->name ?? '-' }}
                                <div class="text-xs">الهاتف: {{ $student->phone ?? '-' }}</div>
                                <div class="text-xs">الرقم المدني: {{ $student->national_id ?? '-' }}</div>
                                <div class="text-xs">الجنس/النوع: {{ ($student->gender ?? null) === 'male' ? 'ذكر' : 'أنثى' }}</div>
                            </td>

                            {{-- Competition level --}}    
                            <td class="px-4 py-3">
                                {{ $competition->level ?? '-' }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                               {{ $competition->committee?->title ?? '-' }}
                            </td>

                            {{-- Student status --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $statusClasses[$competition->student_status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ __($competition->student_status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center text-sm text-gray-500 font-mono" dir="ltr">
                                @if ($competition->present_at)
                                    {{ $competition->present_at->format('H:i') }}
                                    <div class="text-[10px] text-gray-400 font-sans mt-0.5">
                                        {{ $competition->present_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}

                            <td class="px-4 py-3">
                                @if ($competition->student_status == 'registration')
                                    <a href="{{ route('student.show', $competition->id) }}"
                                        class="inline-flex items-center px-3 py-1.5
                                           bg-blue-600 text-white text-sm font-medium
                                           rounded-lg hover:bg-blue-700 transition">
                                        تسجيل حضور
                                    </a>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>
