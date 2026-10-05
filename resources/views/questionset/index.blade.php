<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div class="flex items-center gap-4">

                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    باقات الأسئلة
                </h1>

                <span
                    class="text-sm md:text-base font-semibold
                           bg-green-100 text-green-700
                           px-4 py-2 rounded-lg
                           border border-green-200"
                >
                    {{ $level }}
                </span>

            </div>

            <div class="flex flex-wrap items-center gap-2">

                {{-- Create --}}
                <a
                    href="{{ route('questionset.create', $levelId) }}"
                    class="inline-flex items-center
                           px-5 py-3
                           bg-green-600 text-white
                           rounded-lg
                           hover:bg-green-700
                           transition shadow-sm"
                >
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء باقة جديدة
                </a>

                {{-- Print --}}
                <a
                    href="{{ route('questionset.print') }}"
                    class="inline-flex items-center
                           px-4 py-3
                           bg-yellow-500 text-white
                           rounded-lg
                           hover:bg-yellow-600
                           transition"
                >
                    <i class="fas fa-print ml-2"></i>
                    طباعة
                </a>

            </div>

        </div>


        {{-- Questionsets --}}
        @if ($questionsets->isNotEmpty())

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($questionsets as $set)

                    <div
                        class="border rounded-xl shadow-sm overflow-hidden transition
                               hover:shadow-md
                               {{ $set->questions_count == 5
                                    ? 'bg-green-50 border-green-300'
                                    : 'bg-white border-gray-200' }}"
                    >

                        {{-- Card Header --}}
                        <div class="p-5 border-b border-gray-100">

                            <div class="flex items-start justify-between gap-3">

                                <h3 class="text-xl font-bold text-gray-800">
                                    {{ $set->title }}
                                </h3>

                                @if ($set->selected)

                                    <span
                                        class="shrink-0
                                               bg-green-100 text-green-800
                                               text-xs font-medium
                                               px-2.5 py-1
                                               rounded-full
                                               inline-flex items-center"
                                    >
                                        <i class="fas fa-check ml-1"></i>
                                        تم اختيارها
                                    </span>

                                @endif

                            </div>

                            <div class="mt-3 flex items-center text-sm text-gray-600">

                                <i class="fas fa-layer-group ml-2"></i>

                                <span>
                                    {{ $level }}
                                </span>

                            </div>

                        </div>


                        {{-- Card Footer --}}
                        <div
                            class="bg-gray-50 px-5 py-4
                                   flex flex-col sm:flex-row
                                   sm:items-center
                                   sm:justify-between
                                   gap-3"
                        >

                            <div class="flex flex-wrap gap-2">

                                {{-- Show --}}
                                <a
                                    href="{{ route('questionset.show', $set) }}"
                                    class="inline-flex items-center
                                           px-3 py-2
                                           bg-blue-500 text-white
                                           text-sm rounded-lg
                                           hover:bg-blue-600
                                           transition"
                                >
                                    <i class="fas fa-eye ml-1"></i>

                                    عرض الأسئلة
                                    ({{ $set->questions_count }})

                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route('questionset.edit', $set) }}"
                                    class="inline-flex items-center
                                           px-3 py-2
                                           bg-yellow-500 text-white
                                           text-sm rounded-lg
                                           hover:bg-yellow-600
                                           transition"
                                >
                                    <i class="fas fa-edit ml-1"></i>
                                    تعديل
                                </a>

                            </div>


                            {{-- Delete --}}
                            <form
                                action="{{ route('questionset.destroy', $set) }}"
                                method="POST"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center
                                           px-3 py-2
                                           bg-red-600 text-white
                                           text-sm rounded-lg
                                           hover:bg-red-700
                                           transition"
                                >
                                    <i class="fas fa-trash ml-1"></i>
                                    حذف
                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- Empty State --}}
            <div
                class="bg-white border border-gray-200
                       rounded-xl shadow-sm
                       text-center py-14 px-6"
            >

                <div
                    class="mx-auto w-20 h-20
                           bg-gray-100 rounded-full
                           flex items-center justify-center mb-5"
                >
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-700 mb-2">
                    لا توجد باقات أسئلة
                </h3>

                <p class="text-gray-500 mb-6">
                    ابدأ بإنشاء باقة أسئلة جديدة
                </p>

                <a
                    href="{{ route('questionset.create', $levelId) }}"
                    class="inline-flex items-center
                           px-5 py-3
                           bg-green-600 text-white
                           rounded-lg
                           hover:bg-green-700
                           transition shadow-sm"
                >
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء باقة جديدة
                </a>

            </div>

        @endif

    </div>

</x-app-layout>