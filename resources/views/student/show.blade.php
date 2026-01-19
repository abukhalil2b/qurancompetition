<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">تفاصيل المتسابق</h2>
                @if($student->active)
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold">نشط</span>
                @else
                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-bold">معطل</span>
                @endif
            </div>

            <div class="p-6">
                @php
                    $age = $student->dob ? \Carbon\Carbon::parse($student->dob)->age : '-';
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 dir-rtl">
                    
                    <div class="col-span-1 sm:col-span-2 md:col-span-3">
                        <label class="block text-xs text-gray-400 font-medium">الاسم</label>
                        <div class="text-gray-900 font-bold text-lg">{{ $student->name }}</div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الهاتف</label>
                        <div class="text-gray-800">{{ $student->phone ?? '-' }}</div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الرقم المدني</label>
                        <div class="text-gray-800">{{ $student->national_id ?? '-' }}</div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الجنس</label>
                        <div class="text-gray-800">{{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الجنسية</label>
                        <div class="text-gray-800">{{ $student->nationality }}</div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">العمر / الميلاد</label>
                        <div class="text-gray-800 flex items-center gap-2">
                            <span class="text-xs bg-gray-100 px-2 rounded-full">({{ $age }} سنة)</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الموقع</label>
                        <div class="text-gray-800 text-sm">
                            {{ $student->state ?? '-' }} / {{ $student->wilaya ?? '-' }}
                            @if($student->qarya) <br> <span class="text-gray-500">{{ $student->qarya }}</span> @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-medium">الفرع</label>
                        <div class="text-gray-800 font-semibold">{{ $student->level ?? '-' }}</div>
                    </div>

                    <div class="col-span-1 sm:col-span-2 md:col-span-3">
                        <label class="block text-xs text-gray-400 font-medium">ملحوظة</label>
                        <div class="text-gray-800 bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm">
                            {{ $student->note ?: 'لا توجد ملاحظات' }}
                        </div>
                    </div>

                </div>

                @if (auth()->id() == 1)
                    <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                        <a href="{{ route('student.edit', $student) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-sm font-bold rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h6M7 9l10-4-2 14H5l2-10z"></path>
                            </svg>
                            تعديل
                        </a>

                        <form action="{{ route('student.destroy', $student) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذا المتسابق؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 text-sm font-bold rounded-lg transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a2 2 0 00-2 2v0a2 2 0 002 2h4a2 2 0 002-2v0a2 2 0 00-2-2m-4 0V3"></path>
                                </svg>
                                حذف
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800">تسجيل الحضور</h3>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $stage->title }} <span class="mx-1">•</span> مستوى: <span class="font-semibold text-blue-600">{{ $level }}</span>
                </p>
            </div>

            <form action="{{ route('competition.student.present') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختر اللجنة</label>
                    <div class="relative">
                        <select name="committee_id" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 pr-10 pl-4 text-right">
                            @foreach ($committees as $committee)
                                <option value="{{ $committee->committee_id }}">
                                    {{ $committee->committee_title }} - {{ $committee->center_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-lg shadow-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تأكيد الحضور
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>