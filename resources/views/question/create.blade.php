<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-3xl font-bold mb-6">{{ $questionset->title }}</h2>
        <h4 class="text-3xl font-bold mb-6">{{ $questionset->branch }}</h4>
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
                <label for="difficulties" class="block mb-1 font-medium">الصعوبة</label>
                <select name="difficulties" id="difficulties" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">اختر الصعوبة</option>
                    <option value="سهل" {{ old('difficulties') == 'سهل' ? 'selected' : '' }}>سهل</option>
                    <option value="متوسط" {{ old('difficulties') == 'متوسط' ? 'selected' : '' }}>متوسط</option>
                    <option value="صعب" {{ old('difficulties') == 'صعب' ? 'selected' : '' }}>صعب</option>
                </select>
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
