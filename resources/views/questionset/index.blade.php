<x-app-layout>


    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">باقات الأسئلة</h1>
                <p class="text-xl text-gray-600 mt-2">{{ $level }}</p>
            </div>
            <a href="{{ route('questionset.create', $level) }}"
                class="mt-4 md:mt-0 px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center shadow-md">
                <i class="fas fa-plus ml-2"></i>
                إنشاء باقة جديدة
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($questionsets as $set)
                <div
                    class="{{ $set->questions_count == 5 ? 'bg-green-100 text-green-700' : 'bg-white' }} rounded-xl shadow-md overflow-hidden border border-gray-200">
                    <!-- Card Header -->
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-bold">{{ $set->title }}</h3>
                            @if ($set->selected)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="fas fa-check ml-1 text-xs"></i>
                                    تم اختيارها
                                </span>
                            @endif
                        </div>
                        <div class="mt-2 flex items-center text-gray-600">
                            <i class="fas fa-code-branch ml-2"></i>
                            <span>{{ $set->branch }}</span>
                        </div>
                    </div>


                    <!-- Card Footer - Actions -->
                    <div class="bg-gray-50 px-5 py-4 flex justify-between items-center">
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('questionset.show', $set) }}"
                                class="px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition flex items-center">
                                <i class="fas fa-eye ml-1"></i>
                                عرض
                                الأسئلة

                                ({{ $set->questions_count }})
                            </a>
                            <a href="{{ route('questionset.edit', $set) }}"
                                class="px-3 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition flex items-center">
                                <i class="fas fa-edit ml-1"></i>
                                تعديل
                            </a>
                        </div>

                        <div class="flex space-x-2 rtl:space-x-reverse">


                            <form action="{{ route('questionset.destroy', $set) }}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition flex items-center">
                                    <i class="fas fa-trash ml-1"></i>
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if ($questionsets->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">لا توجد مجموعات أسئلة</h3>
                <p class="text-gray-500 mb-6">ابدأ بإنشاء باقة أسئلة جديدة</p>
                <a href="{{ route('questionset.create') }}"
                    class="px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition inline-flex items-center shadow-md">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء باقة جديدة
                </a>
            </div>
        @endif


    </div>

</x-app-layout>
