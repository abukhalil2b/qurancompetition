<x-app-layout>
    <div class="p-6">

        <div class="bg-white p-6 rounded-xl shadow w-full md:w-2/3 mx-auto">

            <h2 class="text-xl font-bold mb-4 text-center">
                تعديل بيانات المتسابق: <span class="text-blue-600">{{ $student->name }}</span>
            </h2>

            {{-- الأخطاء --}}
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
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- الاسم --}}
                    <div>
                        <label class="font-semibold mb-1 block">الاسم</label>
                        <input type="text" name="name" class="form-input w-full" required
                               value="{{ old('name', $student->name) }}">
                    </div>

                    {{-- الجنس --}}
                    <div>
                        <label class="font-semibold mb-1 block">الجنس</label>
                        <select name="gender" class="form-select w-full" required>
                            <option value="">اختر</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    {{-- الهاتف --}}
                    <div>
                        <label class="font-semibold mb-1 block">الهاتف (8 أرقام)</label>
                        <input type="text" name="phone" maxlength="8" class="form-input w-full"
                               value="{{ old('phone', $student->phone) }}">
                    </div>

                    {{-- الرقم المدني --}}
                    <div>
                        <label class="font-semibold mb-1 block">الرقم المدني</label>
                        <input type="text" name="national_id" maxlength="11" class="form-input w-full"
                               value="{{ old('national_id', $student->national_id) }}">
                    </div>

                    {{-- الجنسية --}}
                    <div>
                        <label class="font-semibold mb-1 block">الجنسية</label>
                        <input type="text" name="nationality" class="form-input w-full" required
                               value="{{ old('nationality', $student->nationality) }}">
                    </div>

                    {{-- تاريخ الميلاد --}}
                    <div>
                        <label class="font-semibold mb-1 block">تاريخ الميلاد</label>
                        <input type="date" name="dob" class="form-input w-full" required
                               value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '') }}">
                    </div>

                    {{-- الولاية --}}
                    <div>
                        <label class="font-semibold mb-1 block">الولاية</label>
                        <input type="text" name="state" class="form-input w-full" required
                               value="{{ old('state', $student->state) }}">
                    </div>

                    {{-- المنطقة --}}
                    <div>
                        <label class="font-semibold mb-1 block">المنطقة</label>
                        <input type="text" name="wilaya" class="form-input w-full" required
                               value="{{ old('wilaya', $student->wilaya) }}">
                    </div>

                    {{-- القرية --}}
                    <div>
                        <label class="font-semibold mb-1 block">القرية</label>
                        <input type="text" name="qarya" class="form-input w-full"
                               value="{{ old('qarya', $student->qarya) }}">
                    </div>

                    {{-- المستوى/الفرع --}}
                    <div>
                        <label class="font-semibold mb-1 block">الفرع</label>
                        <input type="text" name="level" class="form-input w-full"
                               value="{{ old('level', $student->level) }}">
                    </div>

                    {{-- تاريخ التسجيل --}}
                    <div>
                        <label class="font-semibold mb-1 block">تاريخ التسجيل</label>
                        <input type="date" name="registration_date" class="form-input w-full"
                               value="{{ old('registration_date', $student->registration_date ? \Carbon\Carbon::parse($student->registration_date)->format('Y-m-d') : '') }}">
                    </div>

                    {{-- الحالة --}}
                    <div>
                        <label class="font-semibold mb-1 block">الحالة</label>
                        <select name="active" class="form-select w-full">
                            <option value="1" {{ old('active', $student->active) ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ old('active', $student->active) === 0 ? 'selected' : '' }}>معطل</option>
                        </select>
                    </div>

                    {{-- ملاحظات --}}
                    <div class="md:col-span-2">
                        <label class="font-semibold mb-1 block">ملاحظات</label>
                        <textarea name="note" class="form-input w-full" rows="4">{{ old('note', $student->note) }}</textarea>
                    </div>

                </div>

                <div class="flex justify-between pt-4">
                    <a href="{{ route('student.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        رجوع
                    </a>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        تحديث بيانات المتسابق
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
