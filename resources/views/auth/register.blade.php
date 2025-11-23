{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">إنشاء حساب جديد</h2>
                <p class="mt-2 text-sm text-gray-600">املأ النموذج أدناه للتسجيل</p>
            </div>

            {{-- ------------------- FORM ------------------- --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                {{-- ---------- National ID (Alpine: nationalIdChecker) ---------- --}}
                <div x-data="nationalIdChecker()" class="space-y-2">
                    <label for="national_id" class="text-sm font-medium text-gray-700">الرقم المدني</label>

                    <input id="national_id" type="number"
                        x-model="nationalId" @keydown.enter.prevent="check()"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                               focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="أدخل الرقم المدني" name="national_id" required />

                    <template x-if="message">
                        <p x-text="message" :class="messageClass" class="text-sm mt-1"></p>
                    </template>

                    <button type="button" @click="check()"
                        x-text="loading ? 'جارٍ التحقق...' : 'تحقق من الرقم المدني'"
                        class="w-full h-8 flex items-center justify-center px-3 bg-white border rounded text-xs
                               hover:bg-gray-50 transition"
                        :disabled="loading"></button>

                    <x-input-error :messages="$errors->get('national_id')" class="text-sm text-red-600" />
                </div>

                {{-- ---------- Names + Nationality (Alpine: registrationForm) ---------- --}}
                <div x-data="registrationForm()" x-init="sync()" class="space-y-6">

                    {{-- Nationality two‑box selector --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">الجنسية</label>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Omani --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="nationality_type" value="omani"
                                       class="sr-only peer" x-model="nationalityType">
                                <div class="h-8 flex items-center justify-center bg-gray-100 rounded-lg
                                            border-2 border-transparent
                                            peer-checked:border-[#854d0e] peer-checked:bg-[#e3ccb4]
                                            hover:bg-gray-200 transition">
                                    <span class="text-sm font-medium text-gray-700 peer-checked:text-[#854d0e]">
                                        عُماني
                                    </span>
                                </div>
                            </label>

                            {{-- Other --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="nationality_type" value="other"
                                       class="sr-only peer" x-model="nationalityType">
                                <div class="h-8 flex items-center justify-center bg-gray-100 rounded-lg
                                            border-2 border-transparent
                                            peer-checked:border-[#854d0e] peer-checked:bg-[#e3ccb4]
                                            hover:bg-gray-200 transition">
                                    <span class="text-sm font-medium text-gray-700 peer-checked:text-[#854d0e]">
                                        أخرى
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Free‑text nationality (shown only if "other") --}}
                    <div x-show="nationalityType === 'other'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700">حدد الجنسية</label>
                        <input type="text" name="nationality" x-model="nationalityText"
                               x-bind:required="nationalityType === 'other'"
                               placeholder="مثال: مصري"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                      focus:ring-indigo-500 focus:border-indigo-500 mt-1" />
                        <x-input-error :messages="$errors->get('nationality')" class="mt-2 text-sm text-red-600" />
                    </div>

                    {{-- Omani name‑parts (required only if nationalityType === 'omani') --}}
                    <template x-if="nationalityType === 'omani'">
                        <div class="grid grid-cols-1 gap-4" x-cloak>
                            {{-- First name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">الاسم الأول</label>
                                <input type="text" name="first_name" x-model="first_name" @input="sync()"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                              focus:ring-indigo-500 focus:border-indigo-500 mt-1"
                                       required>
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-sm text-red-600" />
                            </div>

                            {{-- Second name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">اسم الأب</label>
                                <input type="text" name="second_name" x-model="second_name" @input="sync()"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                              focus:ring-indigo-500 focus:border-indigo-500 mt-1"
                                       required>
                                <x-input-error :messages="$errors->get('second_name')" class="mt-2 text-sm text-red-600" />
                            </div>

                            {{-- Third name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">اسم الجد</label>
                                <input type="text" name="third_name" x-model="third_name" @input="sync()"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                              focus:ring-indigo-500 focus:border-indigo-500 mt-1"
                                       required>
                                <x-input-error :messages="$errors->get('third_name')" class="mt-2 text-sm text-red-600" />
                            </div>

                            {{-- Last name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">اسم العائلة</label>
                                <input type="text" name="last_name" x-model="last_name" @input="sync()"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                              focus:ring-indigo-500 focus:border-indigo-500 mt-1"
                                       required>
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-sm text-red-600" />
                            </div>
                        </div>
                    </template>

                    {{-- Hidden nationality value when Omani --}}
                    <input type="hidden" name="nationality"
                           x-bind:value="nationalityType === 'omani' ? 'Omani' : nationalityText">

                    {{-- Full name (always visible & editable) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                        <input type="text" name="name" x-model="full_name" @input="edited = true"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                      focus:ring-indigo-500 focus:border-indigo-500 mt-1" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                    </div>
                </div>

                {{-- ---------- Gender ---------- --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">الجنس</label>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Male --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="male"
                                   class="sr-only peer" checked>
                            <div class="h-8 flex items-center justify-center bg-gray-100 rounded-lg
                                        border-2 border-transparent
                                        peer-checked:border-[#854d0e] peer-checked:bg-[#e3ccb4]
                                        hover:bg-gray-200 transition">
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-[#854d0e]">
                                    ذكر
                                </span>
                            </div>
                        </label>

                        {{-- Female --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="female"
                                   class="sr-only peer">
                            <div class="h-8 flex items-center justify-center bg-gray-100 rounded-lg
                                        border-2 border-transparent
                                        peer-checked:border-[#854d0e] peer-checked:bg-[#e3ccb4]
                                        hover:bg-gray-200 transition">
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-[#854d0e]">
                                    أنثى
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ---------- Phone ---------- --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">رقم الجوال</label>
                    <input type="tel" name="phone" :value="old('phone')"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                  focus:ring-indigo-500 focus:border-indigo-500 mt-1" required>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                </div>

                {{-- ---------- Password ---------- --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                    <input id="password" type="password" name="password"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm
                                  focus:ring-indigo-500 focus:border-indigo-500 mt-1" required>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    <p class="mt-2 text-xs text-gray-500">
                        يجب أن تكون كلمة المرور 8 أحرف على الأقل
                    </p>
                </div>

                {{-- Submit --}}
                <div>
                    <x-primary-button class="w-full justify-center">
                        تسجيل الحساب
                    </x-primary-button>
                </div>
            </form>

            {{-- Sign‑in link --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        تسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>

{{-- -------------------- Alpine helpers -------------------- --}}
<script>
/* National‑ID checker */
function nationalIdChecker () {
    return {
        nationalId   : '',
        message      : '',
        messageClass : '',
        loading      : false,

        async check () {
            /* simple client format */
            if (!/^\d{6,20}$/.test(this.nationalId)) {
                this.message      = 'الرقم المدني غير صالح.';
                this.messageClass = 'text-red-600';
                return;
            }

            this.loading = true;
            try {
                const { data } = await axios.get('{{ route('check_national_id') }}', {
                    params: { national_id: this.nationalId }
                });

                if (data.exists) {
                    this.message      = 'الرقم المدني موجود مسبقًا.';
                    this.messageClass = 'text-red-600';
                } else {
                    this.message      = 'الرقم المدني متاح للاستخدام.';
                    this.messageClass = 'text-green-600';
                }
            } catch (e) {
                this.message      = 'حدث خطأ أثناء التحقق.';
                this.messageClass = 'text-red-600';
            } finally {
                this.loading = false;
            }
        }
    };
}

/* Names & Nationality handler */
function registrationForm () {
    return {
        /* nationality */
        nationalityType : '{{ old('nationality_type', 'omani') }}', // 'omani' | 'other'
        nationalityText : '{{ old('nationality') }}',

        /* name parts */
        first_name  : '{{ old('first_name') }}',
        second_name : '{{ old('second_name') }}',
        third_name  : '{{ old('third_name') }}',
        last_name   : '{{ old('last_name') }}',

        /* full name */
        full_name : '{{ old('name') }}',
        edited    : false,

        sync () {
            if (this.nationalityType === 'omani' && !this.edited) {
                this.full_name = [this.first_name, this.second_name,
                                  this.third_name, this.last_name]
                                 .filter(Boolean).join(' ');
            }
        }
    };
}
</script>
