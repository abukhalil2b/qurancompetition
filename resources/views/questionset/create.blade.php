<x-app-layout>

    <div class="p-6 max-w-2xl mx-auto">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            <h2 class="text-xl font-bold text-gray-800 mb-6">
                إنشاء باقة أسئلة جديدة
            </h2>


            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="mb-5 p-4 bg-red-50 border border-red-200
                            text-red-700 rounded-lg">

                    <ul class="list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                action="{{ route('questionset.store') }}"
                method="POST"
                class="space-y-5"
            >

                @csrf


                {{-- Title --}}
                <div>

                    <label
                        for="title"
                        class="block mb-1.5 font-semibold text-gray-700"
                    >
                        العنوان
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title') }}"
                        class="w-full border border-gray-300
                               rounded-lg px-3 py-2.5
                               focus:outline-none
                               focus:ring-2 focus:ring-green-500
                               focus:border-green-500"
                        required
                    >

                    @error('title')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Level --}}
                <div>

                    <label
                        class="block mb-1.5 font-semibold text-gray-700"
                    >
                        المستوى
                    </label>

                    <div
                        class="w-full
                               bg-gray-50
                               border border-gray-200
                               rounded-lg
                               px-3 py-2.5
                               text-gray-700"
                    >
                        {{ $level }}
                    </div>

                    <input
                        type="hidden"
                        name="level"
                        value="{{ $levelId }}"
                    >

                </div>


                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-2">

                    <a
                        href="{{ route('questionset.index', $levelId) }}"
                        class="px-4 py-2.5
                               bg-gray-200 text-gray-700
                               rounded-lg
                               hover:bg-gray-300
                               transition"
                    >
                        إلغاء
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2.5
                               bg-green-600 text-white
                               rounded-lg
                               hover:bg-green-700
                               transition"
                    >
                        إنشاء
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>