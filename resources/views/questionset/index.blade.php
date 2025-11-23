<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">قائمة مجموعات الأسئلة</h2>
            <a href="{{ route('questionset.create') }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                إنشاء مجموعة جديدة
            </a>
        </div>

    

        <div class="overflow-x-auto bg-white rounded-xl shadow p-4">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50">
                <tr class="text-right">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">العنوان</th>
                    <th class="px-4 py-2">الفرع</th>
                    <th class="px-4 py-2">محدد</th>
                    <th class="px-4 py-2">الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($questionsets as $set)
                    <tr class="text-right hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $set->title }}</td>
                        <td class="px-4 py-2">{{ $set->branch }}</td>
                        <td class="px-4 py-2">{{ $set->selected ? 'نعم' : 'لا' }}</td>
                        <td class="px-4 py-2 space-x-2 flex justify-end rtl:space-x-reverse">
                            <a href="{{ route('questionset.show', $set) }}"
                               class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">عرض</a>
<a href="{{ route('question.index',$set->id) }}" class="group">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M4 4h16v16H4V4z" fill="currentColor" />
                                <path d="M8 8h8v8H8V8z" fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                                الأسئلة
                            </span>
                        </div>
                    </a>
                            <a href="{{ route('questionset.edit', $set) }}"
                               class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">تعديل</a>
                            <form action="{{ route('questionset.destroy', $set) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
