<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto bg-white rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6">إنشاء مجموعة أسئلة جديدة</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('questionset.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block mb-1 font-medium">العنوان</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

            <div>
                <label for="branch" class="block mb-1 font-medium">الفرع</label>
                <input type="text" name="branch" id="branch" value="{{ old('branch') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       required>
            </div>

            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <input type="checkbox" name="selected" id="selected" value="1" class="h-4 w-4" {{ old('selected') ? 'checked' : '' }}>
                <label for="selected" class="font-medium">محدد</label>
            </div>

            <div class="flex justify-end space-x-3 rtl:space-x-reverse mt-4">
                <a href="{{ route('questionset.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">إلغاء</a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">إنشاء</button>
            </div>
        </form>
    </div>
</x-app-layout>
