<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-3xl font-bold mb-6">تعديل السؤال</h2>
        <h4 class="text-2xl font-bold mb-6">{{ $questionset->title }} – {{ $questionset->branch }}</h4>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('question.update', $question) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Content --}}
            <div>
                <label for="content" class="block mb-1 font-medium">محتوى السؤال</label>
                <textarea name="content" id="content" rows="4"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                          required>{{ old('content', $question->content) }}</textarea>
            </div>

            {{-- Difficulty --}}
            <div>
                <label for="difficulties" class="block mb-1 font-medium">الصعوبة</label>
                <select name="difficulties" id="difficulties"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                    <option value="">اختر الصعوبة</option>

                    <option value="السهلة"
                        {{ old('difficulties', $question->difficulties) == 'السهلة' ? 'selected' : '' }}>
                        السهلة
                    </option>

                    <option value="المتوسطة"
                        {{ old('difficulties', $question->difficulties) == 'المتوسطة' ? 'selected' : '' }}>
                        المتوسطة
                    </option>

                    <option value="الصعبة"
                        {{ old('difficulties', $question->difficulties) == 'الصعبة' ? 'selected' : '' }}>
                        الصعبة
                    </option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3 rtl:space-x-reverse mt-4">
                <a href="{{ route('questionset.show', $questionset) }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    إلغاء
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    تحديث
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
