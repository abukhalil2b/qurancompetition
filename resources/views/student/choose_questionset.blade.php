<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6">

            <h2 class="text-xl font-bold mb-6">
                <div>اختيار الباقة للمتسابق: {{ $student->name }}</div>
                <div class="text-sm text-gray-500">المستوى: {{ $student->level }}</div>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($questionsets as $questionset)
                    <div class="border rounded-xl p-5 shadow-sm hover:shadow-md transition">

                        <h3 class="text-lg font-semibold mb-2">
                            {{ $questionset->title }}
                        </h3>

                        <p class="text-sm text-gray-600">
                            المستوى: {{ $questionset->level }}
                        </p>

                        <p class="text-sm text-gray-600">
                            عدد الأسئلة: {{ $questionset->questions_count }}
                        </p>

                        <a href="{{ route('student.save_questionset', [$competition->id, $questionset->id]) }}"
                           class="mt-4 block text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            اختيار الباقة
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full">
                        لا توجد باقات متاحة لهذا المستوى
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
