<x-app-layout>
    <div class="p-4 md:p-6 max-w-7xl mx-auto" dir="rtl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">إدارة اللجان</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- Right/Top: Committees List (2/3 width on desktop) --}}
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-100">
                        <h2 class="font-bold text-gray-700">اللجان الحالية</h2>
                    </div>

                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3 border-b">اللجنة والمركز</th>
                                    <th class="px-6 py-3 border-b">أعضاء اللجنة  </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($committees as $committee)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold text-blue-900">{{ $committee->title }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded">{{ __($committee->gender) }}</span>
                                                <span class="text-xs text-gray-500">{{ $committee->center->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($committee->judges as $judge)
                                                    <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-[11px]">
                                                        {{ $judge->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 text-xs italic">لا يوجد محكمين</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-10 text-center text-gray-400">لا توجد لجان مسجلة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile View: Cards --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($committees as $committee)
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-blue-900">{{ $committee->title }}</h3>
                                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ __($committee->gender) }}</span>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">{{ $committee->center->title }}</div>
                                
                                <div class="bg-gray-50 p-2 rounded-lg">
                                    <p class="text-[10px] font-bold text-gray-400 mb-1 uppercase">المحكمين:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($committee->judges as $judge)
                                            <span class="text-xs text-gray-700 bg-white border border-gray-200 px-2 py-0.5 rounded shadow-sm">
                                                {{ $judge->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">لا يوجد أعضاء</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Left/Bottom: Create Form (1/3 width on desktop) --}}
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-5 sticky top-6">
                    <h2 class="text-lg font-bold mb-5 flex items-center gap-2 text-gray-800">
                        <span class="p-1.5 bg-blue-600 rounded-lg text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        </span>
                        إضافة لجنة جديدة
                    </h2>

                    <form action="{{ route('committee.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">اسم اللجنة</label>
                            <input type="text" name="title" 
                                class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                placeholder="مثال: لجنة مسقط" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
                                <select name="gender" class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                     <option value="males text-right">{{ __('males') }}</option>
                                     <option value="females text-right">{{ __('females') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">المركز</label>
                                <select name="center_id" class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->id }}">{{ $center->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100 mt-2">
                            حفظ اللجنة
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>