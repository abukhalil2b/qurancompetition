<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-3xl font-bold mb-4">{{ $questionset->title }}</h2>

        <div class="flex justify-end mb-4">
            <a href="{{ route('question.create', $questionset) }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                إضافة سؤال جديد
            </a>
        </div>


        @foreach ($questionsByLevel as $difficult => $questions)
            <!-- Category Header -->
            <h3 class="text-2xl font-semibold bg-gray-100 p-3 rounded mt-6 mb-3">
                 {{ $difficult }}
            </h3>

            <table class="min-w-full divide-y divide-gray-200 table-auto mb-6">
                <thead class="bg-gray-50">
                <tr class="text-right">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">المحتوى</th>
                    <th class="px-4 py-2">الفرع</th>
                    <th class="px-4 py-2">الإجراءات</th>
                </tr>
                </thead>

                <tbody>
                @foreach($questions as $question)
                    <tr class="text-right hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $question->content }}</td>
                        <td class="px-4 py-2">{{ $question->branch }}</td>

                        <td class="px-4 py-2 space-x-2 flex justify-end rtl:space-x-reverse">
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
                </tbody>
            </table>

        @endforeach

    </div>
</x-app-layout>
