<x-app-layout>

    <div class="min-h-screen bg-gray-50 p-4" dir="rtl">

        <div class="max-w-2xl mx-auto">

            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">

                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ $questionset->title }}
                </h1>

                <div class="flex flex-wrap gap-4 text-gray-600">

                    <div class="flex items-center gap-2">
                        <span class="font-medium">
                            المستوى:
                        </span>

                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            {{ $questionset->level }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-medium">
                            عدد الأسئلة:
                        </span>

                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                            {{ $questionset->questions()->count() }}
                        </span>
                    </div>

                </div>

            </div>


            {{-- Form --}}
            <div class="bg-white rounded-2xl shadow-sm p-6" x-data="questionForm()">

                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    إضافة سؤال جديد
                </h2>


                <form action="{{ route('question.store', $questionset) }}" method="POST" class="space-y-6">

                    @csrf

                    <div class="flex gap-1">

                        {{-- Riwaya --}}
                        <div>

                            <label for="riwaya" class="block text-gray-700 font-medium mb-2">
                                الرواية
                            </label>

                            <select name="riwaya" id="riwaya"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3
                               focus:outline-none focus:border-green-500
                               focus:ring-1 focus:ring-green-500"
                                required>

                                <option value="حفص" @selected(old('riwaya', 'حفص') === 'حفص')>
                                    حفص
                                </option>

                                <option value="شعبة" @selected(old('riwaya') === 'شعبة')>
                                    شعبة
                                </option>

                            </select>

                        </div>


                        {{-- Juz --}}
                        <div>
                            <label for="juz" class="block text-gray-700 font-medium mb-2">الجزء </label>
                            <select name="juz" id="juz"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                required>

                                <option value="">
                                    اختر الجزء
                                </option>

                                @foreach ($juzs as $juz)
                                    <option value="{{ $juz }}" @selected(old('juz') == $juz)>
                                        الجزء {{ $juz }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    {{-- Starting Surat --}}
                    <div>
                        <label for="quran_surat_id" class="block text-gray-700 font-medium mb-2">
                            من سورة
                        </label>

                        <select name="quran_surat_id" id="quran_surat_id" x-model="selectedSurat"
                            @change="loadAyas('from')"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
               focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                            required>
                            <option value="">اختر السورة</option>

                            @foreach ($surats as $surat)
                                <option value="{{ $surat->id }}">
                                    {{ $surat->number }} - {{ $surat->title }}
                                </option>
                            @endforeach
                        </select>

                        @error('quran_surat_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ending Surat --}}
                    <div>
                        <label for="quran_surat_to_id" class="block text-gray-700 font-medium mb-2">
                            إلى سورة
                        </label>

                        <select name="quran_surat_to_id" id="quran_surat_to_id" x-model="selectedSuratTo"
                            @change="loadAyas('to')" :disabled="!selectedSurat"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
               disabled:bg-gray-100 disabled:text-gray-400
               focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                            required>
                            <option value="">اختر السورة</option>

                            <template x-for="surat in availableEndSurats" :key="surat.id">
                                <option :value="String(surat.id)" x-text="surat.number + ' - ' + surat.title">
                                </option>
                            </template>
                        </select>

                        @error('quran_surat_to_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Loading --}}
                    <template x-if="loadingFrom || loadingTo">

                        <div class="flex items-center gap-2 text-gray-500 text-sm">

                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>

                            جاري تحميل الآيات...

                        </div>

                    </template>

                    <div class="flex gap-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            {{-- Starting Ayah --}}
                            <div>
                                <label for="aya_from" class="block text-gray-700 font-medium mb-2">
                                    من الآية
                                </label>

                                <select name="aya_from" id="aya_from" x-model="selectedAyaFrom"
                                    @change="validateAyaRange()" :disabled="loadingFrom || !selectedSurat"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                   disabled:bg-gray-100 disabled:text-gray-400"
                                    required>
                                    <option value="">اختر الآية</option>

                                    <template x-for="aya in ayasFrom" :key="aya.id">
                                        <option :value="String(aya.number)" x-text="'الآية ' + aya.number">
                                        </option>
                                    </template>
                                </select>

                                @error('aya_from')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ending Ayah --}}
                            <div>
                                <label for="aya_to" class="block text-gray-700 font-medium mb-2">
                                    إلى الآية
                                </label>

                                <select name="aya_to" id="aya_to" x-model="selectedAyaTo"
                                    :disabled="loadingTo || !selectedSuratTo || !selectedAyaFrom"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                   disabled:bg-gray-100 disabled:text-gray-400"
                                    required>
                                    <option value="">اختر الآية</option>

                                    <template x-for="aya in availableToAyas" :key="aya.id">
                                        <option :value="String(aya.number)" x-text="'الآية ' + aya.number">
                                        </option>
                                    </template>
                                </select>

                                @error('aya_to')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Selected Quran Preview --}}
                    <template x-if="selectedAyaFrom && selectedAyaTo && selectedSurat && selectedSuratTo">
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">

                            <div class="text-sm text-emerald-700 font-medium mb-2">
                                نطاق الآيات المحدد
                            </div>

                            <div class="text-gray-700">
                                من سورة
                                <strong x-text="suratName(selectedSurat)"></strong>

                                الآية
                                <strong x-text="selectedAyaFrom"></strong>

                                <span class="mx-2 text-emerald-600">إلى</span>

                                سورة
                                <strong x-text="suratName(selectedSuratTo)"></strong>

                                الآية
                                <strong x-text="selectedAyaTo"></strong>
                            </div>

                        </div>
                    </template>

                    {{-- Actions --}}
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">

                        <a href="{{ route('questionset.show', $questionset) }}"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700
                               rounded-lg hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>


                        <button type="submit"
                            :disabled="loadingFrom ||
                                loadingTo ||
                                !selectedSurat ||
                                !selectedSuratTo ||
                                !selectedAyaFrom ||
                                !selectedAyaTo"
                            class="px-6 py-2.5 bg-green-600 text-white
           rounded-lg hover:bg-green-700
           disabled:bg-gray-300 disabled:cursor-not-allowed
           transition-colors font-medium">

                            إضافة السؤال

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <script>
        function questionForm() {
            return {
                surats: @js(
    $surats
        ->map(
            fn($surat) => [
                'id' => (string) $surat->id,
                'number' => $surat->number,
                'title' => $surat->title,
            ],
        )
        ->values(),
),

                selectedSurat: @js(old('quran_surat_id', '')),
                selectedSuratTo: @js(old('quran_surat_to_id', '')),

                selectedAyaFrom: @js(old('aya_from', '')),
                selectedAyaTo: @js(old('aya_to', '')),

                ayasFrom: [],
                ayasTo: [],

                loadingFrom: false,
                loadingTo: false,

                async init() {
                    // Restore previously selected values after validation errors.
                    if (this.selectedSurat) {
                        await this.loadAyas('from', false);
                    }

                    if (this.selectedSuratTo) {
                        await this.loadAyas('to', false);
                    }
                },

                get availableEndSurats() {
                    if (!this.selectedSurat) {
                        return [];
                    }

                    const start = this.surats.find(
                        surat => surat.id === String(this.selectedSurat)
                    );

                    if (!start) {
                        return [];
                    }

                    return this.surats.filter(
                        surat => surat.number >= start.number
                    );
                },

                get availableToAyas() {
                    if (!this.selectedAyaFrom) {
                        return [];
                    }

                    // Same Surah: ending Ayah cannot be before starting Ayah.
                    if (this.selectedSurat === this.selectedSuratTo) {
                        return this.ayasTo.filter(
                            aya => Number(aya.number) >= Number(this.selectedAyaFrom)
                        );
                    }

                    // Different Surahs: all Ayahs in ending Surah are available.
                    return this.ayasTo;
                },

                suratName(id) {
                    const surat = this.surats.find(
                        surat => surat.id === String(id)
                    );

                    return surat ? surat.title : '';
                },

                async loadAyas(type, reset = true) {
                    const isFrom = type === 'from';

                    const suratId = isFrom ?
                        this.selectedSurat :
                        this.selectedSuratTo;

                    if (reset) {
                        if (isFrom) {
                            this.selectedAyaFrom = '';
                        } else {
                            this.selectedAyaTo = '';
                        }
                    }

                    if (!suratId) {
                        if (isFrom) {
                            this.ayasFrom = [];
                        } else {
                            this.ayasTo = [];
                        }

                        return;
                    }

                    if (isFrom) {
                        this.loadingFrom = true;
                    } else {
                        this.loadingTo = true;
                    }

                    try {
                        const url = @json(route('quran.surats.ayas', [
                                'quranSurat' => '__SURAT__',
                            ])).replace('__SURAT__', suratId);

                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Failed to load ayas');
                        }

                        const data = await response.json();

                        if (isFrom) {
                            this.ayasFrom = data;
                        } else {
                            this.ayasTo = data;
                        }

                    } catch (error) {
                        console.error(error);

                        if (isFrom) {
                            this.ayasFrom = [];
                        } else {
                            this.ayasTo = [];
                        }

                        alert('تعذر تحميل الآيات. يرجى المحاولة مرة أخرى.');

                    } finally {
                        if (isFrom) {
                            this.loadingFrom = false;
                        } else {
                            this.loadingTo = false;
                        }
                    }
                },

                validateAyaRange() {
                    if (
                        this.selectedSurat === this.selectedSuratTo &&
                        this.selectedAyaFrom &&
                        this.selectedAyaTo &&
                        Number(this.selectedAyaTo) < Number(this.selectedAyaFrom)
                    ) {
                        this.selectedAyaTo = '';
                    }
                },
            };
        }
    </script>

</x-app-layout>
