<x-app-layout>
    <div class="p-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <h2 class="text-xl font-semibold mb-4">
                اختيار الباقة: {{ $student->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($questionsets as $questionset)
                    <div class="border rounded-xl p-4 shadow-sm">

                        <div class="text-lg font-semibold">
                            {{ $questionset->title }}
                        </div>

                        <div class="text-gray-600">
                            المستوى: {{ $questionset->level }}
                        </div>

                        <div class="text-gray-600">
                            عدد الأسئلة: {{ $questionset->questions_count }}
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('student.save_questionset', [$competition->id, $questionset->id]) }}"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg block text-center hover:bg-green-700">
                                اختيار
                            </a>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
