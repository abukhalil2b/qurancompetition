<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-xl shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">الأسئلة لباقة: {{ $questionset->title }}</h2>
            <a href="{{ route('question.create', $questionset) }}"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                إضافة سؤال جديد
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50 text-right">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">المحتوى</th>
                        <th class="px-4 py-2">المستوى</th>
                        <th class="px-4 py-2">الفرع</th>
                        <th class="px-4 py-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-right">
                    @foreach ($questions as $question)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $question->content }}</td>
                            <td class="px-4 py-2">{{ $question->level }}</td>
                            <td class="px-4 py-2">{{ $question->branch }}</td>
                            <td class="px-4 py-2 flex space-x-2 rtl:space-x-reverse justify-end">
                                <a href="{{ route('question.edit', $question) }}"
                                    class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">تعديل</a>
                                <form action="{{ route('question.destroy', $question) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($questions->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center px-4 py-2 text-gray-500">لا توجد أسئلة حتى الآن</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
