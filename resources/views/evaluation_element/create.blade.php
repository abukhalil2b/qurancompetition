<x-app-layout>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen">
        <div class="max-w-lg mx-auto">
            <div class="bg-white shadow-xl rounded-xl p-8 sm:p-10" 
                 x-data="{ parentId: '{{ old('parent_id') }}' }">
                
                <h1 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">
                    إضافة عنصر تقييم
                </h1>

                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('evaluation_element.index') }}"
                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                        <svg class="h-4 w-4 transform rotate-180 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        العودة إلى القائمة
                    </a>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                        <p class="font-bold">خطأ!</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('evaluation_element.store') }}" method="POST">
                    @csrf

                    <!-- Title Input -->
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            العنوان (العنصر/العنوان الرئيسي)
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 @error('title') border-red-500 @enderror"
                            placeholder="مثال: حفظ سورة البقرة، أو: قسم الحفظ والتسميع">
                    </div>

                    <!-- Parent Selection (Controls visibility of Branch/Score) -->
                    <div class="mb-6">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">
                            العنوان الرئيسي (اختياري)
                        </label>
                        <select id="parent_id" name="parent_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 @error('parent_id') border-red-500 @enderror"
                            x-model="parentId">
                            <option value="">-- لا يوجد (هذا عنوان رئيسي) --</option>
                            @foreach ($headers as $header)
                                <option value="{{ $header->id }}"
                                    {{ old('parent_id') == $header->id ? 'selected' : '' }}>
                                    {{ $header->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            اختر عنواناً إذا كان هذا العنصر فرعياً.
                        </p>
                    </div>

                    <!-- Branch Selection (ONLY for Header Elements) -->
                    <div class="mb-5" x-show="parentId == ''">
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">
                            الفرع (للعناوين الرئيسية)
                        </label>
                        <select id="branch" name="branch" 
                            :required="parentId == ''" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 @error('branch') border-red-500 @enderror">
                            <option value="">اختر فرعاً</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch }}" {{ old('branch') == $branch ? 'selected' : '' }}>
                                    {{ $branch }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 text-indigo-600 font-medium">
                            يظهر هذا الحقل فقط عند إنشاء عنوان رئيسي.
                        </p>
                    </div>

                    <!-- Max Score Input (ONLY for Child Elements) -->
                    <div class="mb-6" x-show="parentId != ''">
                        <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">
                            الدرجة القصوى (للعناصر الفرعية)
                        </label>
                        <input type="number" id="max_score" name="max_score" 
                            value="{{ old('max_score') }}" 
                            :required="parentId != ''" 
                            min="1"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 @error('max_score') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 text-indigo-600 font-medium">
                            يظهر هذا الحقل فقط عند إنشاء عنصر فرعي. يجب أن تكون الدرجة أكبر من صفر.
                        </p>
                    </div>
                    
                    <!-- Order Input (Kept visible for both) -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1">
                            ترتيب العرض
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', 0) }}"
                            min="0"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 @error('order') border-red-500 @enderror">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            حفظ عنصر التقييم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>