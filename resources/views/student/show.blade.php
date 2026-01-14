<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">

        <h2 class="text-xl font-bold mb-4 text-center">تفاصيل المتسابق</h2>
        @php
            $age = $student->dob ? \Carbon\Carbon::parse($student->dob)->age : null;
        @endphp
        <div>
            <div><strong>الاسم:</strong> {{ $student->name }}</div>
            <div class="flex gap-3">
                <div><strong>الهاتف:</strong> {{ $student->phone ?? '-' }}</div>
                <div><strong>الرقم المدني:</strong> {{ $student->national_id ?? '-' }}</div>
            </div>
            <div class="flex gap-4">
                <div><strong>الجنس:</strong> {{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>

                <div><strong>الجنسية:</strong> {{ $student->nationality }}</div>
            </div>
            <div class="flex gap-3">
                <div><strong>تاريخ الميلاد:</strong> {{ $student->dob }}</div>
                <div><strong> العمر:</strong> {{ $age }}</div>
            </div>
            <div class="flex gap-3">
                <div><strong>المنطقة:</strong> {{ $student->state ?? '-' }}</div>
                <div><strong>الولاية:</strong> {{ $student->wilaya ?? '-' }}</div>
                <div><strong>القرية:</strong> {{ $student->qarya ?? '-' }}</div>
            </div>
            <div class="flex gap-4">
                <div><strong>الفرع:</strong> {{ $student->level ?? '-' }}</div>
                <div><strong>الحالة:</strong> {{ $student->active ? '' : 'معطل' }}</div>
            </div>
            <div><strong>الملحوظة:</strong> {{ $student->note }}</div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pt-6 gap-4">

            @if (auth()->id() == 1)
                {{-- Actions --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('student.edit', $student) }}"
                        class="flex items-center gap-1 px-3 py-1 bg-yellow-400 text-gray-800 text-sm rounded-lg hover:bg-yellow-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h6M7 9l10-4-2 14H5l2-10z">
                            </path>
                        </svg>
                        تعديل
                    </a>

                    <form action="{{ route('student.destroy', $student) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذا المتسابق؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center gap-1 px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a2 2 0 00-2 2v0a2 2 0 002 2h4a2 2 0 002-2v0a2 2 0 00-2-2m-4 0V3">
                                </path>
                            </svg>
                            حذف
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>

    <div class="mt-4 p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">
        <p class="py-5 text-2xl"> تسجيل حضور المتسابق </p>
        <h2>{{ $stage->title }}. المستوى:{{ $level }}</h2>

        <form action="{{ route('competition.student.present') }}" method="POST">
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <label class="font-semibold mb-1 block">اللجنة</label>
            <select name="committee_id" class="form-input w-full">
                @foreach ($committees as $committee)
                    <option value="{{ $committee->committee_id }}">
                        {{ $committee->committee_title }} - {{ $committee->center_title }}
                    </option>
                @endforeach
            </select>

            <div class="py-5">
                <button
                    class="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">تسجيل
                    الحضور</button>
            </div>
        </form>

    </div>



</x-app-layout>
