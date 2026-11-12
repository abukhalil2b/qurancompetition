<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-xl shadow">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50 text-right">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">المحتوى</th>
                        <th class="px-4 py-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-right">
                    @foreach ($questions as $question)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>

                            <td class="px-4 py-2">
                                {{ $question->content }}
                            </td>

                            <td class="px-4 py-2">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('question.edit', $question) }}"
                                        class="px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition">
                                        تعديل
                                    </a>

                                    <form action="{{ route('question.destroy', $question) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition">
                                            حذف
                                        </button>
                                    </form>
                                </div>
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
