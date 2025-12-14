<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6">تعديل باقة الأسئلة</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('questionset.update', $questionset) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div>
                <label for="title" class="block mb-1 font-medium">العنوان</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $questionset->title) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

            {{-- Level / Branch --}}
            <div>
                <label for="level" class="block mb-1 font-medium">المستوى</label>
                <select name="level" id="level"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="حفظ"
                        {{ old('level', $questionset->level) == 'حفظ' ? 'selected' : '' }}>
                        حفظ
                    </option>

                    <option value="حفظ وتفسير"
                        {{ old('level', $questionset->level) == 'حفظ وتفسير' ? 'selected' : '' }}>
                        حفظ وتفسير
                    </option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3 rtl:space-x-reverse mt-4">
                <a href="{{ route('questionset.index') }}"
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
