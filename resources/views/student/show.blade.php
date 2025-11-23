<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">

        <h2 class="text-xl font-bold mb-4 text-center">تفاصيل الطالب</h2>
        @php
            $age = $student->dob ? \Carbon\Carbon::parse($student->dob)->age : null;
        @endphp
        <div class="space-y-2">
            <div><strong>الاسم:</strong> {{ $student->name }}</div>
            <div><strong>الجنس:</strong> {{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
            <div><strong>الهاتف:</strong> {{ $student->phone ?? '-' }}</div>
            <div><strong>الرقم المدني:</strong> {{ $student->national_id ?? '-' }}</div>
            <div><strong>الجنسية:</strong> {{ $student->nationality }}</div>
            <div><strong>تاريخ الميلاد:</strong> {{ $student->dob }}</div>
            <div><strong>العمر:</strong> {{ $age ?? '-' }}</div>
            <div><strong>الولاية:</strong> {{ $student->state }}</div>
            <div><strong>المنطقة:</strong> {{ $student->wilaya }}</div>
            <div><strong>القرية:</strong> {{ $student->qarya ?? '-' }}</div>
            <div><strong>الفرع:</strong> {{ $student->branch ?? '-' }}</div>
            <div><strong>تاريخ التسجيل:</strong> {{ $student->registration_date ?? '-' }}</div>
        </div>

        <div class="flex justify-between pt-6">
            <a href="{{ route('student.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                رجوع
            </a>

            {{-- Optional: Link to Competitions / Committees --}}
            <a href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                اللجان / المسابقات
            </a>
        </div>

    </div>
</x-app-layout>
