<x-app-layout>
    <div class="p-6">

        <div class="bg-white p-6 rounded-xl shadow w-full md:w-1/2 mx-auto">

            <h2 class="text-xl font-bold mb-4 text-center">إضافة محكم جديد</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc mr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- name --}}
                <div>
                    <label class="font-semibold mb-1 block">الاسم</label>
                    <input type="text" name="name" class="form-input w-full" required value="{{ old('name') }}">
                </div>

                {{-- gender --}}
                <div>
                    <label class="font-semibold mb-1 block">الجنس</label>
                    <select name="gender" class="form-select w-full" required>
                        <option value="">اختر</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold mb-1 block">نوع المستخدم</label>
                    <select name="gender" class="form-select w-full" required>
                        @foreach($userTypes as $userType)
                        <option value="{{ $userType }}">{{ __($userType) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- national id --}}
                <div>
                    <label class="font-semibold mb-1 block">الرقم المدني</label>
                    <input type="text" name="national_id" maxlength="10"
                           class="form-input w-full" value="{{ old('national_id') }}">
                </div>

                <p class="text-gray-600 text-sm">
                    * كلمة المرور الافتراضية: <span class="font-semibold">123456</span>
                </p>

                <div class="flex justify-between pt-4">
                    <a href="{{ route('user.index') }}"
                       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        رجوع
                    </a>

                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        حفظ المحكم
                    </button>
                </div>

            </form>
            
        </div>
    </div>
</x-app-layout>
