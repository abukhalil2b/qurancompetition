<x-app-layout>
    <div class="p-6">

        <div class="bg-white p-6 rounded-xl shadow w-full md:w-2/3 mx-auto">

            <h2 class="text-xl font-bold mb-4 text-center">إضافة متسابق جديد</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc mr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.store') }}" method="POST" class="space-y-6" x-data="{ gender: '{{ old('gender', 'male') }}', level: '{{ old('level', 'حفظ') }}' }">
    @csrf

    {{-- Required Fields Section --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
            <span class="text-red-500 ml-2">*</span>
            البيانات الأساسية (مطلوبة)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Name --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الاسم الكامل <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    required
                    value="{{ old('name') }}"
                    placeholder="أدخل الاسم الكامل"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            {{-- Gender --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الجنس <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="gender" x-model="gender">
                <div class="flex gap-2">
                    <button 
                        type="button"
                        @click="gender = 'male'"
                        :class="gender === 'male' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                        class="flex-1 px-4 py-2 border-2 rounded-lg font-semibold transition-all duration-200">
                        ذكر
                    </button>
                    <button 
                        type="button"
                        @click="gender = 'female'"
                        :class="gender === 'female' ? 'bg-pink-600 text-white border-pink-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                        class="flex-1 px-4 py-2 border-2 rounded-lg font-semibold transition-all duration-200">
                        أنثى
                    </button>
                </div>
            </div>

            {{-- Nationality --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الجنسية <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nationality" 
                    required
                    value="{{ old('nationality', 'عماني') }}"
                    placeholder="عماني"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            {{-- Level --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    المستوى <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="level" x-model="level">
                <div class="flex gap-2">
                    <button 
                        type="button"
                        @click="level = 'حفظ'"
                        :class="level === 'حفظ' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                        class="flex-1 px-4 py-3 border-2 rounded-lg font-semibold transition-all duration-200">
                        حفظ
                    </button>
                    <button 
                        type="button"
                        @click="level = 'حفظ وتفسير'"
                        :class="level === 'حفظ وتفسير' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                        class="flex-1 px-4 py-3 border-2 rounded-lg font-semibold transition-all duration-200">
                        حفظ وتفسير
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Optional Fields Section --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h3 class="text-lg font-bold text-gray-900 mb-4">
            البيانات الإضافية (اختيارية)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Date of Birth --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    تاريخ الميلاد
                </label>
                <input 
                    type="date" 
                    name="dob" 
                    value="{{ old('dob') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    رقم الهاتف
                </label>
                <input 
                    type="text" 
                    name="phone" 
                    maxlength="8" 
                    value="{{ old('phone') }}"
                    placeholder="8 أرقام"
                    pattern="[0-9]{8}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                <p class="text-xs text-gray-500 mt-1">يجب أن يكون 8 أرقام بالضبط</p>
            </div>

            {{-- National ID --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الرقم المدني
                </label>
                <input 
                    type="text" 
                    name="national_id" 
                    maxlength="11" 
                    value="{{ old('national_id') }}"
                    placeholder="حتى 11 رقم"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                <p class="text-xs text-gray-500 mt-1">الحد الأقصى 11 رقم</p>
            </div>

            {{-- State --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    المحافظة
                </label>
                <input 
                    type="text" 
                    name="state" 
                    value="{{ old('state') }}"
                    placeholder="أدخل المحافظة"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            {{-- Wilaya --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    الولاية
                </label>
                <input 
                    type="text" 
                    name="wilaya" 
                    value="{{ old('wilaya') }}"
                    placeholder="أدخل الولاية"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            {{-- Qarya --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    القرية
                </label>
                <input 
                    type="text" 
                    name="qarya" 
                    value="{{ old('qarya') }}"
                    placeholder="أدخل القرية"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
        <a href="{{ route('student.index') }}" 
           class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
            رجوع
        </a>

        <button type="submit" 
                class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all duration-200">
            حفظ المتسابق
        </button>
    </div>

</form>
        </div>
    </div>
</x-app-layout>
