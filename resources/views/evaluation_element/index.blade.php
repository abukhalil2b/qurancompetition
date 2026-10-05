<x-app-layout>

    <div class="p-6" x-data="{ open: false }" @keydown.escape.window="open = false">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">

            <h2 class="text-xl font-bold text-gray-800">
                عناصر التقييم - {{ $level }}
            </h2>

            <button type="button" @click="open = true"
                class="px-4 py-2 bg-green-600 text-white rounded-lg
                       hover:bg-green-700 transition">
                إضافة عنصر جديد - {{ $level }}
            </button>

        </div>


        {{-- Elements Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-right border-collapse">

                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">

                            <th class="px-6 py-4 text-sm font-bold text-gray-600">
                                العنوان
                            </th>

                            <th class="px-6 py-4 text-sm font-bold text-gray-600">
                                الحد الأعلى
                            </th>

                            <th class="px-6 py-4 text-sm font-bold text-gray-600 text-center">
                                إجراءات
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($elements as $el)
                            <tr class="hover:bg-blue-50/50 transition-colors">

                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                    {{ $el->title }}
                                </td>

                                <td class="px-6 py-4 text-sm">

                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5
                                               rounded-full text-xs font-bold
                                               bg-gray-100 text-gray-700">
                                        {{ $el->max_score }} درجات
                                    </span>

                                </td>

                                <td class="px-6 py-4 text-center">

                                    <a href="{{ route('evaluation_element.edit', $el->id) }}"
                                        class="inline-flex items-center gap-1.5
                                               px-4 py-1.5
                                               bg-blue-50 text-blue-600
                                               border border-blue-100
                                               rounded-lg text-xs font-bold
                                               hover:bg-blue-600 hover:text-white
                                               transition-all active:scale-95">

                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>

                                        تعديل

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    لا توجد عناصر تقييم لهذا المستوى.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Modal --}}
        <div x-cloak x-show="open" x-transition.opacity
            class="fixed inset-0 z-40 flex items-center justify-center
                   bg-black/40 px-4">

            {{-- Modal Panel --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false"
                class="bg-white w-full max-w-lg p-6 rounded-xl shadow-xl">

                <div class="flex items-center justify-between mb-6">

                    <h3 class="text-lg font-bold text-gray-800">
                        إضافة عنصر تقييم جديد
                    </h3>

                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 text-xl">
                        &times;
                    </button>

                </div>


                <form method="POST" action="{{ route('evaluation_element.store') }}">

                    @csrf

                    {{-- Title --}}
                    <div class="mb-4">

                        <label class="block font-semibold text-gray-700 mb-1">
                            العنوان
                        </label>

                        <input type="text" name="title"
                            class="w-full border border-gray-300 rounded-lg p-2.5
                                   focus:border-green-500 focus:ring-green-500"
                            required>

                    </div>


                    {{-- Max Score --}}
                    <div class="mb-4">

                        <label class="block font-semibold text-gray-700 mb-1">
                            الحد الأعلى للنقاط
                        </label>

                        <input type="number" name="max_score" min="1" max="100"
                            class="w-full border border-gray-300 rounded-lg p-2.5
                                   focus:border-green-500 focus:ring-green-500"
                            required>

                    </div>


                    {{-- Level --}}
                    <div class="mb-6">

                        <label class="block font-semibold text-gray-700 mb-1">
                            المستوى
                        </label>

                        <div
                            class="w-full bg-gray-50 border border-gray-200
                                    rounded-lg px-3 py-2.5 text-gray-700">
                            {{ $level }}
                        </div>

                        <input type="hidden" name="level_id" value="{{ $levelId }}">

                    </div>


                    {{-- Actions --}}
                    <div class="flex justify-end gap-2">

                        <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700
                                   rounded-lg hover:bg-gray-300 transition">
                            إلغاء
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white
                                   rounded-lg hover:bg-green-700 transition">
                            حفظ
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
