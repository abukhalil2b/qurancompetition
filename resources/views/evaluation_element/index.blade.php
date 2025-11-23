<x-app-layout>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
                عناصر التقييم 📊
            </h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif


            <div class="mb-6 flex justify-end">
                <a href="{{ route('evaluation_element.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    ➕ إضافة عنصر تقييم جديد
                </a>
            </div>

            <div class="space-y-6">
                @forelse ($elements as $header)
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-indigo-200">
                        
                        {{-- Header Title and Actions --}}
                        <div class="p-5 bg-indigo-50 border-b border-indigo-200 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-indigo-800 flex-grow">
                                <span class="ml-2">#</span> 
                                {{ $header->title }}
                                <span class="text-sm font-normal text-indigo-500 ml-2">({{ $header->order }})</span>
                            </h2>
                            <div class="flex items-center space-x-4 rtl:space-x-reverse text-sm font-medium text-gray-600">
                                <span class="hidden md:inline">الفرع: <span class="text-indigo-600 font-semibold">{{ $header->branch }}</span></span>
                                
                                {{-- Header Actions --}}
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <a href="{{ route('evaluation_element.edit', $header) }}"
                                        class="text-yellow-600 hover:text-yellow-800 transition">
                                        تعديل
                                    </a>
                                    
                                    <form action="{{ route('evaluation_element.destroy', $header) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف العنوان الرئيسي «{{ $header->title }}» وجميع العناصر الفرعية المرتبطة به؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if ($header->childElements->isNotEmpty())
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">العناصر الفرعية (الإجراءات):</h3>
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($header->childElements as $element)
                                        <li
                                            class="py-3 flex justify-between items-center text-gray-800 hover:bg-gray-50 transition duration-100 px-2 rounded-md">
                                            
                                            <div class="flex flex-col flex-grow">
                                                <span class="font-medium">
                                                    <span class="text-gray-400">{{ $header->order }}.{{ $element->order }}</span>
                                                    {{ $element->title }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                                <div
                                                    class="text-sm font-mono bg-green-100 text-green-800 px-3 py-1 rounded-full whitespace-nowrap">
                                                    الدرجة القصوى: {{ $element->max_score }}
                                                </div>
                                                
                                                {{-- Child Actions --}}
                                                <div class="flex space-x-2 rtl:space-x-reverse ml-4">
                                                    <a href="{{ route('evaluation_element.edit', $element) }}"
                                                        class="text-yellow-600 hover:text-yellow-800 text-sm transition">
                                                        تعديل
                                                    </a>
                                                    
                                                    <form action="{{ route('evaluation_element.destroy', $element) }}" method="POST"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف عنصر التقييم «{{ $element->title }}»؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm transition">
                                                            حذف
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="p-5 text-gray-500 italic">
                                لا توجد عناصر تقييم فرعية تحت هذا العنوان بعد. يمكنك <a href="{{ route('evaluation_element.create') }}?parent_id={{ $header->id }}" class="text-indigo-600 hover:underline">إضافة عنصر فرعي الآن</a>.
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white shadow rounded-lg">
                      
                        <h3 class="mt-2 text-sm font-medium text-gray-900">لا يوجد عناصر تقييم</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            ابدأ بإضافة أول عنوان تقييم لك.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('evaluation_element.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                إضافة عنصر
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>