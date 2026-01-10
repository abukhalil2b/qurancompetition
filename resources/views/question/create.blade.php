<x-app-layout>
    <div class="min-h-screen bg-gray-50 p-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $questionset->title }}</h1>
                
                <div class="flex flex-wrap gap-4 text-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">المستوى:</span>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $questionset->level }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium">عدد الأسئلة:</span>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ $questionset->questions()->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 text-red-700 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">يوجد أخطاء</span>
                </div>
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">إضافة سؤال جديد</h2>
                
                <form action="{{ route('question.store', $questionset) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Content Field -->
                    <div>
                        <label for="content" class="block text-gray-700 font-medium mb-2">
                            محتوى السؤال
                        </label>
                        <textarea 
                            name="content" 
                            id="content" 
                            rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 text-gray-700 placeholder-gray-400"
                            placeholder="اكتب محتوى السؤال هنا..."
                            required
                            autofocus>{{ old('content') }}</textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('questionset.show', $questionset) }}"
                           class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>
                        
                        <button type="submit"
                                class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            إضافة السؤال
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>