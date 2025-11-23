<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6">إضافة سؤال جديد لمجموعة: {{ $questionset->title }}</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('question.store', $questionset) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="content" class="block mb-1 font-medium">محتوى السؤال</label>
                <textarea name="content" id="content" rows="4"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                          required>{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="level" class="block mb-1 font-medium">المستوى</label>
                <select name="level" id="level" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">اختر المستوى</option>
                    <option value="easy" {{ old('level') == 'easy' ? 'selected' : '' }}>سهل</option>
                    <option value="medium" {{ old('level') == 'medium' ? 'selected' : '' }}>متوسط</option>
                    <option value="hard" {{ old('level') == 'hard' ? 'selected' : '' }}>صعب</option>
                </select>
            </div>

            <div>
                <label for="branch" class="block mb-1 font-medium">الفرع</label>
                <input type="text" name="branch" id="branch" value="{{ old('branch', $questionset->branch) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

            <div class="flex justify-end space-x-3 rtl:space-x-reverse mt-4">
                <a href="{{ route('questionset.show', $questionset) }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">إلغاء</a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">إضافة</button>
            </div>
        </form>
    </div>
</x-app-layout>
