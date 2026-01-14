<x-app-layout>
    <div x-data="{ activeTab: 'memorization' }" class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8" dir="rtl">

        {{-- Page Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900">
                لوحة الشرف والنتائج النهائية
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                قائمة الطلاب الذين أتموا المسابقة وتم اعتماد نتائجهم
            </p>
        </div>

        {{-- Tabs Navigation --}}
        <div class="flex justify-center mb-8 border-b border-gray-200">
            <button 
                @click="activeTab = 'memorization'"
                :class="activeTab === 'memorization' 
                    ? 'border-indigo-600 text-indigo-600 bg-indigo-50' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="w-1/3 py-4 px-1 text-center border-b-2 font-bold text-lg transition-all rounded-t-lg">
                🏆 ترتيب الحفظ
            </button>
            
            <button 
                @click="activeTab = 'tafseer'"
                :class="activeTab === 'tafseer' 
                    ? 'border-orange-500 text-orange-600 bg-orange-50' 
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="w-1/3 py-4 px-1 text-center border-b-2 font-bold text-lg transition-all rounded-t-lg">
                📖 ترتيب التفسير
            </button>
        </div>

        {{-- TAB 1: MEMORIZATION LIST --}}
        <div x-show="activeTab === 'memorization'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
             
            <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                @if($memorizationList->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-20">
                                الترتيب
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                اسم الطالب
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">
                                الدرجة (100)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($memorizationList as $index => $comp)
                        <tr class="hover:bg-indigo-50 transition-colors duration-150">
                            {{-- Rank Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                                    {{ $index == 0 ? 'bg-yellow-100 text-yellow-800 border border-yellow-300 shadow-sm' : 
                                      ($index == 1 ? 'bg-gray-100 text-gray-800 border border-gray-300' : 
                                      ($index == 2 ? 'bg-orange-100 text-orange-800 border border-orange-300' : 'bg-white text-gray-500 font-normal')) }} 
                                    font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            
                            {{-- Student Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $comp->student->name }}</div>
                                <div class="text-xs text-gray-500">{{ $comp->student->level }}</div>
                            </td>

                            {{-- Score --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-100 text-indigo-800">
                                    {{ number_format($comp->memorization_score, 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="p-10 text-center text-gray-500">
                        لا توجد نتائج معتمدة حتى الآن.
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB 2: TAFSEER LIST --}}
        <div x-show="activeTab === 'tafseer'" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
             
            <div class="bg-white shadow overflow-hidden sm:rounded-lg border-t-4 border-orange-400">
                @if($tafseerList->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-orange-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-orange-800 uppercase tracking-wider w-20">
                                الترتيب
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-orange-800 uppercase tracking-wider">
                                اسم الطالب
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider w-32">
                                درجة التفسير (40)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tafseerList as $index => $comp)
                        <tr class="hover:bg-orange-50 transition-colors duration-150">
                            {{-- Rank Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-600 font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            
                            {{-- Student Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $comp->student->name }}</div>
                            </td>

                            {{-- Score --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-lg leading-5 font-bold rounded text-orange-700">
                                    {{ number_format($comp->tafseer_score, 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="p-10 text-center text-gray-500">
                        لا توجد نتائج تفسير معتمدة حتى الآن.
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>