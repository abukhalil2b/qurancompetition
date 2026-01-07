<x-app-layout>
    <div class="p-6" x-data="{ filter: '' }">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">قائمة المتسابقين</h2>
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
            <table class="min-w-full divide-y divide-gray-200 table-auto" dir="rtl">
                <thead class="bg-gray-100 rounded-t-xl">
                    <tr class="text-right text-gray-700 uppercase text-sm tracking-wider">
                        <th class="px-4 py-3 rounded-tr-xl">#</th>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">الجنس</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">الرقم المدني</th>
                        <th class="px-4 py-3">المستوى</th>
                        <th class="px-4 py-3 rounded-tl-xl">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr 
                            class="text-right hover:bg-gray-50 transition-colors duration-200"
                            x-data="{ 
                                // Added spaces between fields for better matching
                                searchStr: '{{ mb_strtolower($student->name . ' ' . $student->state . ' ' . ($student->level ?? '')) }}' 
                            }"
                            x-show="filter === '' || searchStr.includes(filter.toLowerCase())"
                        >
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium">{{ $student->name }}</td>
                            <td class="px-4 py-3">{{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                            <td class="px-4 py-3">{{ $student->phone ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->national_id ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $student->level ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('student.show', $student) }}"
                                    class="inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    تسجيل حضور
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>