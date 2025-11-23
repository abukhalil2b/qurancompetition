<x-app-layout>
    <div class="p-6">

        <div class="bg-white p-6 rounded-xl shadow w-full md:w-2/3 mx-auto">

            <h2 class="text-xl font-bold mb-4 text-center">تعديل بيانات الطالب: **{{ $student->name }}**</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc mr-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.update', $student) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Name --}}
                    <div>
                        <label class="font-semibold mb-1 block">الاسم</label>
                        <input type="text" name="name" class="form-input w-full" required 
                               value="{{ old('name', $student->name) }}">
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="font-semibold mb-1 block">الجنس</label>
                        <select name="gender" class="form-select w-full" required>
                            <option value="">اختر</option>
                            <option value="male" 
                                {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" 
                                {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="font-semibold mb-1 block">الهاتف (8 أرقام)</label>
                        <input type="text" name="phone" maxlength="8" class="form-input w-full" 
                               value="{{ old('phone', $student->phone) }}">
                    </div>

                    {{-- National ID --}}
                    <div>
                        <label class="font-semibold mb-1 block">الرقم المدني</label>
                        <input type="text" name="national_id" maxlength="11" class="form-input w-full" 
                               value="{{ old('national_id', $student->national_id) }}">
                    </div>

                    {{-- Nationality --}}
                    <div>
                        <label class="font-semibold mb-1 block">الجنسية</label>
                        <input type="text" name="nationality" class="form-input w-full" required 
                               value="{{ old('nationality', $student->nationality) }}">
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label class="font-semibold mb-1 block">تاريخ الميلاد</label>
                        <input type="date" name="dob" class="form-input w-full" required 
                               value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '') }}">
                        </div>

                    {{-- Age --}}
                    <div>
                        <label class="font-semibold mb-1 block">العمر</label>
                        <input type="text" name="age" class="form-input w-full" 
                               value="{{ old('age', $student->age) }}">
                    </div>

                    {{-- State --}}
                    <div>
                        <label class="font-semibold mb-1 block">الولاية</label>
                        <input type="text" name="state" class="form-input w-full" required 
                               value="{{ old('state', $student->state) }}">
                    </div>

                    {{-- Wilaya --}}
                    <div>
                        <label class="font-semibold mb-1 block">المنطقة</label>
                        <input type="text" name="wilaya" class="form-input w-full" required 
                               value="{{ old('wilaya', $student->wilaya) }}">
                    </div>

                    {{-- Qarya --}}
                    <div>
                        <label class="font-semibold mb-1 block">القرية</label>
                        <input type="text" name="qarya" class="form-input w-full" 
                               value="{{ old('qarya', $student->qarya) }}">
                    </div>

                    {{-- Branch --}}
                    <div>
                        <label class="font-semibold mb-1 block">الفرع</label>
                        <input type="text" name="branch" class="form-input w-full" 
                               value="{{ old('branch', $student->branch) }}">
                    </div>

                    {{-- Registration Date --}}
                    <div>
                        <label class="font-semibold mb-1 block">تاريخ التسجيل</label>
                        <input type="date" name="registration_date" class="form-input w-full" 
                               value="{{ old('registration_date', $student->registration_date ? \Carbon\Carbon::parse($student->registration_date)->format('Y-m-d') : '') }}">
                    </div>

                </div>

                <div class="flex justify-between pt-4">
                    <a href="{{ route('student.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        رجوع
                    </a>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        تحديث بيانات الطالب
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>