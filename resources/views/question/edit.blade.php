<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 to-green-50 p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-emerald-800 mb-2">تعديل السؤال</h1>
                <div class="flex flex-col md:flex-row gap-2 md:items-center">
                    <span class="text-xl font-semibold text-emerald-700">{{ $questionset->title }}</span>
                    <span class="hidden md:block text-emerald-600 mx-2">–</span>
                    <span class="inline-block bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $questionset->branch }}
                    </span>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-red-800 font-semibold mb-1">يوجد أخطاء في المدخلات</h3>
                        <ul class="text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Edit Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border border-emerald-100">
                <form action="{{ route('question.update', $question) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Content Field -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label for="content" class="block text-lg font-semibold text-emerald-800">
                                محتوى السؤال
                            </label>
                            <span class="text-sm text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                                إجباري
                            </span>
                        </div>
                        <textarea 
                            name="content" 
                            id="content" 
                            rows="6"
                            class="w-full px-4 py-3 border-2 border-emerald-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all duration-200 text-lg text-emerald-900 placeholder-emerald-400"
                            placeholder="أدخل محتوى السؤال هنا..."
                            required
                            autofocus>{{ old('content', $question->content) }}</textarea>
                        <p class="mt-2 text-sm text-emerald-600">
                            يمكنك كتابة السؤال بالكامل مع التفاصيل المطلوبة
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-emerald-100">
                        <div class="flex flex-col sm:flex-row gap-3 justify-end">
                            <a href="{{ route('questionset.show', $questionset) }}"
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 font-medium rounded-xl hover:from-gray-200 hover:to-gray-300 transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                إلغاء والعودة
                            </a>
                            
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        
                    </div>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-amber-800">حالة السؤال</p>
                            <p class="text-sm text-amber-600">نشط وجاهز للاستخدام</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">المستوى</p>
                            <p class="text-sm text-blue-600">{{ $questionset->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>