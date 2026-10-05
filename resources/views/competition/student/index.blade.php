<x-app-layout>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('committee.index') }}" class="hover:text-black font-bold text-xl transition">
                        اللجان
                    </a>

                   
                  
                </div>

                <h1 class="text-2xl font-bold text-slate-800">
                    المتسابقون {{ $stage->title }}
                </h1>

            </div>

            <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold">
                {{ $competitions->count() }} متسابق
            </div>

        </div>


        {{-- Add students --}}
        <form method="POST" action="{{ route('competition.student.store', $center->id) }}" x-data="{
            selected: [],
            allIds: {{ Js::from($unregisteredStudents->pluck('id')->values()) }},
        
            toggleAll() {
                if (this.selected.length === this.allIds.length) {
                    this.selected = [];
                } else {
                    this.selected = [...this.allIds];
                }
            },
        
            isSelected(id) {
                return this.selected.includes(id);
            }
        }">

            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Section header --}}
                <div
                    class="px-6 py-4 bg-slate-50 border-b border-slate-200
                            flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                    <div>
                        <h2 class="font-bold text-slate-800">
                            إضافة متسابقين
                        </h2>

                        <p class="text-xs text-slate-500 mt-1">
                            اختر المتسابقون الذين تريد تسجيلهم في {{ $stage->title }}.
                        </p>
                    </div>

                    @if ($unregisteredStudents->isNotEmpty())
                        <button type="button" @click="toggleAll()"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">

                            <span x-show="selected.length !== allIds.length">
                                تحديد الكل
                            </span>

                            <span x-show="selected.length === allIds.length">
                                إلغاء تحديد الكل
                            </span>
                            ({{ $unregisteredStudents->count() }}) متسابق
                        </button>
                    @endif

                </div>


                @if ($unregisteredStudents->isEmpty())

                    <div class="px-6 py-12 text-center">

                        <svg class="w-12 h-12 mx-auto text-emerald-500 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>

                        <h3 class="font-bold text-slate-700">
                            جميع المتسابقون مسجلون
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            لا يوجد طلاب متبقون لإضافتهم إلى المسابقة.
                        </p>

                    </div>
                @else
                    {{-- Students --}}
                    <div class="divide-y divide-slate-100">

                        @foreach ($unregisteredStudents as $student)
                            <label
                                class="flex items-center gap-4 px-6 py-4 cursor-pointer
                                       hover:bg-slate-50 transition">

                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                    x-model="selected"
                                    class="w-5 h-5 rounded border-slate-300
                                           text-blue-600 focus:ring-blue-500">

                                <div class="flex-1 min-w-0">

                                    <div class="font-semibold text-slate-800">
                                        {{ $student->name }}
                                    </div>

                                    <div class="text-xs text-slate-500 mt-1">
                                        رقم المتسابق: {{ $student->id }}
                                    </div>

                                </div>

                                <span
                                    class="text-xs px-2.5 py-1 rounded-full
                                             bg-slate-100 text-slate-600">
                                    المستوى {{ $student->level }}
                                </span>

                            </label>
                        @endforeach

                    </div>


                    {{-- Submit --}}
                    <div
                        class="px-6 py-4 bg-slate-50 border-t border-slate-200
                                flex flex-col sm:flex-row sm:items-center
                                sm:justify-between gap-3">

                        <p class="text-sm text-slate-500">
                            تم تحديد
                            <span class="font-bold text-slate-800" x-text="selected.length"></span>
                            متسابق
                        </p>

                        <button type="submit" :disabled="selected.length === 0"
                            class="inline-flex items-center justify-center gap-2
                                   px-5 py-2.5 rounded-lg
                                   bg-blue-600 text-white font-semibold
                                   hover:bg-blue-700
                                   disabled:bg-slate-300
                                   disabled:cursor-not-allowed
                                   transition">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>

                            إضافة المتسابقين
                        </button>

                    </div>

                @endif

            </div>

        </form>


        {{-- Registered students --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">

                <h2 class="font-bold text-slate-800">
                        المتسابقون المسجلون في {{ $stage->title }}. ({{ $competitions->count() }})
                </h2>

                <p class="text-xs text-slate-500 mt-1">
           {{ $center->title }} / {{ $center->period }}
                </p>

            </div>


            @if ($competitions->isEmpty())

                <div class="px-6 py-12 text-center text-sm text-slate-500">
                    لا يوجد متسابقون مسجلون حتى الآن.
                </div>
            @else
                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-right">

                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-semibold">#</th>
                                <th class="px-6 py-3 font-semibold">المتسابق</th>
                                <th class="px-6 py-3 font-semibold">المستوى</th>
                                <th class="px-6 py-3 font-semibold">اللجنة</th>
                                <th class="px-6 py-3 font-semibold">الحالة</th>
                                <th class="px-6 py-3 font-semibold"> إدارة </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach ($competitions as $competition)
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-slate-800">
                                            {{ $competition->student->name }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $competition->student->phone }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">
                                            المستوى {{ $competition->level }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">

                                        @if ($competition->committee)
                                            <span class="text-slate-700">
                                                {{ $competition->committee->title }}
                                            </span>
                                        @else
                                            <span class="text-amber-600 text-xs">
                                                لم تحدد
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4">

                                        @php
                                            $statusLabels = [
                                                'registration' => 'مسجل في التصفيات',
                                                'present' => 'حاضر',
                                                'with_committee' => 'مع اللجنة',
                                                'withdraw' => 'منسحب',
                                                'waiting_finalization' => 'بانتظار الاعتماد',
                                                'finish_competition' => 'أنهى المسابقة',
                                            ];
                                        @endphp

                                        <span class="text-xs text-slate-600">
                                            {{ $statusLabels[$competition->student_status] ?? $competition->student_status }}
                                        </span>

                                    </td>
                                    <td>
                                        <a href="{{ route('competition.student.edit', $competition->id) }}" class="font-bold">
                                            تعديل ونقل
                                        </a>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
