<x-app-layout>
    <div class="p-6" x-data="{ filter: '' }">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">قائمة المتسابقون</h2>
            <a href="{{ route('student.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                إضافة متسابق جديد
            </a>
        </div>
        
        <div class="mb-4">
            <label for="student-search" class="font-semibold mb-1 block">البحث عن متسابق (بالاسم، الولاية، أو الفرع)</label>
            <input 
                type="text" 
                id="student-search" 
                placeholder="ابدأ بكتابة اسم المتسابق، الولاية، أو الفرع..."
                class="form-input w-full md:w-1/3 border-gray-300 rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                x-model="filter" 
                dir="rtl"
            >
        </div>
        
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-100 rounded-t-xl">
                    <tr class="text-right text-gray-700 uppercase text-sm tracking-wider">
                        <th class="px-4 py-3 text-left rounded-l-xl">#</th>
                        <th class="px-4 py-3 text-left">الاسم</th>
                        <th class="px-4 py-3 text-left">الجنس</th>
                        <th class="px-4 py-3 text-left">العمر</th>
                        <th class="px-4 py-3 text-left">الهاتف</th>
                        <th class="px-4 py-3 text-left">الولاية</th>
                        <th class="px-4 py-3 text-left">فرع المسابقة</th>
                        <th class="px-4 py-3 text-left">حالة المتسابق </th>
                        <th class="px-4 py-3 text-left rounded-r-xl">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        @php
                            $age = $student->dob ? \Carbon\Carbon::parse($student->dob)->age : '-';
                        @endphp
                        
                        <tr class="text-right hover:bg-gray-50 transition-colors duration-200"
                            x-show="filter === '' || 
                                    '{{ mb_strtolower($student->name) }}'.includes(filter.toLowerCase()) || 
                                    '{{ mb_strtolower($student->state) }}'.includes(filter.toLowerCase()) ||
                                    '{{ mb_strtolower($student->level ?? '') }}'.includes(filter.toLowerCase())"
                        >
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $student->name }}</td>
                            <td class="px-4 py-3">{{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                            <td class="px-4 py-3">{{ $age }}</td>
                            <td class="px-4 py-3">{{ $student->phone ?? '-' }}</td>
                            <td class="px-4 py-3">{{ __($student->student_status) }}</td>
                            <td class="px-4 py-3">{{ $student->level ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->active ? '' : 'معطل'}}</td>
                            <td class="px-4 py-3 space-x-1 flex justify-end rtl:space-x-reverse">
                                <a href="{{ route('student.show', $student) }}"
                                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    تسجيل حضور المتسابق
                                </a>
                            
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>