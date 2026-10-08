<x-app-layout>

    <div class="min-h-screen bg-gray-50 p-4" dir="rtl">

        <div class="max-w-2xl mx-auto">

            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ $question->questionset->title }}
                </h1>

                <div class="flex flex-wrap gap-4 text-gray-600">

                    <div class="flex items-center gap-2">
                        <span class="font-medium">
                            المستوى:
                        </span>

                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            {{ $question->questionset->level }}
                        </span>
                    </div>

                </div>

            </div>


            {{-- Form --}}
            <div
                class="bg-white rounded-2xl shadow-sm p-6"
                x-data="questionEditForm()"
                x-init="init()"
            >

                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    تعديل السؤال
                </h2>


                <form
                    action="{{ route('question.update', $question) }}"
                    method="POST"
                    class="space-y-6"
                >

                    @csrf
                    @method('PUT')


                    {{-- Riwaya --}}
                    <div>

                        <label
                            for="riwaya"
                            class="block text-gray-700 font-medium mb-2"
                        >
                            الرواية
                        </label>

                        <select
                            name="riwaya"
                            id="riwaya"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >

                            <option value="حفص"
                                @selected(old('riwaya', $question->riwaya) === 'حفص')>
                                حفص
                            </option>

                            <option value="شعبة"
                                @selected(old('riwaya', $question->riwaya) === 'شعبة')>
                                شعبة
                            </option>

                        </select>

                        @error('riwaya')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Juz --}}
                    <div>

                        <label
                            for="juz"
                            class="block text-gray-700 font-medium mb-2"
                        >
                            الجزء
                        </label>

                        <select
                            name="juz"
                            id="juz"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >

                            <option value="">
                                اختر الجزء
                            </option>

                            @foreach ($juzs as $juz)

                                <option
                                    value="{{ $juz }}"
                                    @selected(old('juz', $question->juz) == $juz)
                                >
                                    الجزء {{ $juz }}
                                </option>

                            @endforeach

                        </select>

                        @error('juz')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Surat --}}
                    <div>

                        <label
                            for="quran_surat_id"
                            class="block text-gray-700 font-medium mb-2"
                        >
                            السورة
                        </label>

                        <select
                            name="quran_surat_id"
                            id="quran_surat_id"
                            x-model="selectedSurat"
                            @change="loadAyas()"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >

                            <option value="">
                                اختر السورة
                            </option>

                            @foreach ($surats as $surat)

                                <option
                                    value="{{ $surat->id }}"
                                >
                                    {{ $surat->number }} - {{ $surat->title }}
                                </option>

                            @endforeach

                        </select>

                        @error('quran_surat_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Loading --}}
                    <template x-if="loading">

                        <div class="flex items-center gap-2 text-gray-500 text-sm">

                            <svg
                                class="animate-spin h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                            >

                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                />

                            </svg>

                            جاري تحميل الآيات...

                        </div>

                    </template>


                    {{-- From Aya --}}
                    <div>

                        <label
                            for="aya_from"
                            class="block text-gray-700 font-medium mb-2"
                        >
                            من الآية
                        </label>

<select name="aya_from"
    id="aya_from"
    x-model="selectedAyaFrom"
    @change="validateAyaRange()"
    :disabled="loading || !selectedSurat"
    class="w-full border border-gray-300 rounded-lg px-4 py-3
           disabled:bg-gray-100 disabled:text-gray-400
           focus:outline-none focus:border-green-500
           focus:ring-1 focus:ring-green-500"
    required
>
    <option value="">اختر الآية</option>

    <template x-for="aya in ayas" :key="aya.id">
        <option
            :value="aya.number"
            x-text="'الآية ' + aya.number"
            :selected="String(aya.number) === String(selectedAyaFrom)"
        ></option>
    </template>
</select>

                        @error('aya_from')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- To Aya --}}
                    <div>

                        <label
                            for="aya_to"
                            class="block text-gray-700 font-medium mb-2"
                        >
                            إلى الآية
                        </label>

                        <select
    name="aya_to"
    id="aya_to"
    x-model="selectedAyaTo"
    :disabled="loading || !selectedSurat || !selectedAyaFrom"
    class="w-full border border-gray-300 rounded-lg px-4 py-3
           disabled:bg-gray-100 disabled:text-gray-400
           focus:outline-none focus:border-green-500
           focus:ring-1 focus:ring-green-500"
    required
>
    <option value="">اختر الآية</option>

    <template x-for="aya in availableToAyas" :key="aya.id">
        <option
            :value="aya.number"
            x-text="'الآية ' + aya.number"
            :selected="String(aya.number) === String(selectedAyaTo)"
        ></option>
    </template>
</select>

                        @error('aya_to')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Selected Quran Preview --}}
                    <template x-if="selectedAyaFrom && selectedAyaTo">

                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">

                            <div class="text-sm text-emerald-700 font-medium mb-2">
                                نطاق الآيات المحدد
                            </div>

                            <div class="text-gray-700">

                                من الآية

                                <span
                                    class="font-bold"
                                    x-text="selectedAyaFrom"
                                ></span>

                                إلى الآية

                                <span
                                    class="font-bold"
                                    x-text="selectedAyaTo"
                                ></span>

                            </div>

                        </div>

                    </template>


                    {{-- Actions --}}
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">

                        <a
                            href="{{ route('questionset.show', $question->questionset_id) }}"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700
                                   rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            إلغاء
                        </a>


                        <button
                            type="submit"
                            :disabled="loading || !selectedAyaFrom || !selectedAyaTo"
                            class="px-6 py-2.5 bg-green-600 text-white
                                   rounded-lg hover:bg-green-700
                                   disabled:bg-gray-300 disabled:cursor-not-allowed
                                   transition-colors font-medium"
                        >
                            حفظ التعديلات
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>

        function questionEditForm() {

            return {

                selectedSurat: @js(old('quran_surat_id', $question->quran_surat_id)),

                selectedAyaFrom: @js(old('aya_from', $question->aya_from)),

                selectedAyaTo: @js(old('aya_to', $question->aya_to)),

                ayas: [],

                loading: false,


                async init() {

                    /*
                     * Load the ayahs for the question's
                     * existing surat when the page opens.
                     */
                    if (this.selectedSurat) {
                        await this.loadAyas(false);
                    }

                },


                async loadAyas(resetValues = true) {

                    if (resetValues) {
                        this.selectedAyaFrom = '';
                        this.selectedAyaTo = '';
                    }

                    this.ayas = [];

                    if (!this.selectedSurat) {
                        return;
                    }

                    this.loading = true;

                    try {

                        const url = @json(
                            route('quran.surats.ayas', [
                                'quranSurat' => '__SURAT__'
                            ])
                        ).replace(
                            '__SURAT__',
                            this.selectedSurat
                        );


                        const response = await fetch(url);


                        if (!response.ok) {
                            throw new Error('Failed to load ayas');
                        }


                        this.ayas = await response.json();

                    } catch (error) {

                        console.error(error);

                    } finally {

                        this.loading = false;

                    }

                },


                validateAyaRange() {

                    if (!this.selectedAyaFrom) {
                        this.selectedAyaTo = '';
                        return;
                    }

                    if (
                        this.selectedAyaTo &&
                        Number(this.selectedAyaTo) <
                        Number(this.selectedAyaFrom)
                    ) {
                        this.selectedAyaTo = '';
                    }

                },


                get availableToAyas() {

                    if (!this.selectedAyaFrom) {
                        return [];
                    }

                    return this.ayas.filter(
                        aya =>
                            Number(aya.number) >=
                            Number(this.selectedAyaFrom)
                    );

                }

            }

        }

    </script>

</x-app-layout>