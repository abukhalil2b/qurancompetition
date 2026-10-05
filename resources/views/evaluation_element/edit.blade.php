<x-app-layout>

    <div class="p-6 max-w-xl mx-auto">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <h2 class="text-xl font-bold text-gray-800 mb-6">
                تعديل عنصر التقييم
            </h2>

            <form
                method="POST"
                action="{{ route('evaluation_element.update', $evaluationElement->id) }}"
            >

                @csrf
                @method('PUT')


                {{-- Title --}}
                <div class="mb-5">

                    <label class="block font-semibold text-gray-700 mb-1">
                        العنوان
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $evaluationElement->title) }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5
                               focus:border-blue-500 focus:ring-blue-500"
                        required
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Max Score --}}
                <div class="mb-5">

                    <label class="block font-semibold text-gray-700 mb-1">
                        الحد الأعلى للنقاط
                    </label>

                    <input
                        type="number"
                        name="max_score"
                        value="{{ old('max_score', $evaluationElement->max_score) }}"
                        min="1"
                        max="100"
                        class="w-full border border-gray-300 rounded-lg p-2.5
                               focus:border-blue-500 focus:ring-blue-500"
                        required
                    >

                    @error('max_score')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Level --}}
                <div class="mb-6">

                    <label class="block font-semibold text-gray-700 mb-1">
                        المستوى
                    </label>

                    <div class="w-full bg-gray-50 border border-gray-200
                                rounded-lg px-3 py-2.5 text-gray-700">
                        {{ $level }}
                    </div>

                </div>


                {{-- Actions --}}
                <div class="flex justify-end gap-2">

                    <a
                        href="{{ route('evaluation_element.index', $evaluationElement->level) }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg
                               hover:bg-gray-300 transition"
                    >
                        إلغاء
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg
                               hover:bg-blue-700 transition"
                    >
                        تحديث
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>