<x-app-layout>
    <div class="p-6" x-data="{ open: false }" @keydown.escape.window="open = false">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">عناصر التقييم
                - {{ $level }}
            </h2>

            <!-- button is inside the x-data scope now -->
            <button @click="open = true" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                إضافة عنصر جديد - مستوى {{ $level }}
            </button>
        </div>


        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">العنوان</th>
                    <th class="p-2 border">الحد الأعلى</th>
                    <th class="p-2 border">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($elements as $el)
                    <tr>
                        <td class="p-2 border">{{ $el->title }}</td>
                        <td class="p-2 border">{{ $el->max_score }}</td>
                        <td class="p-2 border text-center">
                            <a href="{{ route('evaluation_element.edit', $el->id) }}"
                                class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                تعديل
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal -->
        <div x-cloak x-show="open" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40"
            style="display: none;">
            <!-- modal panel -->
            <div class="bg-white w-full max-w-lg p-6 rounded shadow-lg" @click.outside="open = false" x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                <h3 class="text-lg font-bold mb-4">إضافة عنصر تقييم جديد</h3>

                <form method="POST" action="{{ route('evaluation_element.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">العنوان</label>
                        <input type="text" name="title" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">الحد الأعلى للنقاط</label>
                        <input type="number" name="max_score" min="1" max="100"
                            class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">المستوى - {{ $level }}</label>
                        <input type="hidden" name="level" value="{{ $level }}">
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" @click="open=false"
                            class="px-4 py-2 bg-gray-300 rounded ml-2">إلغاء</button>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            حفظ
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>
