
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
                        <span class="font-medium">المستوى:</span>

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
                        <label for="riwaya" class="block text-gray-700 font-medium mb-2">
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
                            <option
                                value="حفص"
                                @selected(old('riwaya', $question->riwaya) === 'حفص')
                            >
                                حفص
                            </option>

                            <option
                                value="شعبة"
                                @selected(old('riwaya', $question->riwaya) === 'شعبة')
                            >
                                شعبة
                            </option>
                        </select>

                        @error('riwaya')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Juz --}}
                    <div>
                        <label for="juz" class="block text-gray-700 font-medium mb-2">
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
                            <option value="">اختر الجزء</option>

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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Starting Surah --}}
                    <div>
                        <label for="quran_surat_id" class="block text-gray-700 font-medium mb-2">
                            من السورة
                        </label>

                        <select
                            name="quran_surat_id"
                            id="quran_surat_id"
                            x-model="selectedSurat"
                            @change="startSuratChanged()"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >
                            <option value="">اختر السورة الأولى</option>

                            <template x-for="surat in surats" :key="surat.id">
                                <option
                                    :value="surat.id"
                                    x-text="surat.number + ' - ' + surat.title"
                                ></option>
                            </template>
                        </select>

                        @error('quran_surat_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Starting Ayah --}}
                    <div>
                        <label for="aya_from" class="block text-gray-700 font-medium mb-2">
                            من الآية
                        </label>

                        <select
                            name="aya_from"
                            id="aya_from"
                            x-model="selectedAyaFrom"
                            @change="validateAyaRange()"
                            :disabled="loadingFrom || !selectedSurat"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   disabled:bg-gray-100 disabled:text-gray-400
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >
                            <option value="">اختر الآية</option>

                            <template x-for="aya in ayasFrom" :key="aya.id">
                                <option
                                    :value="String(aya.number)"
                                    x-text="'الآية ' + aya.number"
                                ></option>
                            </template>
                        </select>

                        @error('aya_from')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ending Surah --}}
                    <div>
                        <label for="quran_surat_to_id" class="block text-gray-700 font-medium mb-2">
                            إلى السورة
                        </label>

                        <select
                            name="quran_surat_to_id"
                            id="quran_surat_to_id"
                            x-model="selectedSuratTo"
                            @change="loadAyas('to')"
                            :disabled="!selectedSurat"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   disabled:bg-gray-100 disabled:text-gray-400
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >
                            <option value="">اختر السورة الأخيرة</option>

                            <template
                                x-for="surat in availableEndSurats"
                                :key="surat.id"
                            >
                                <option
                                    :value="surat.id"
                                    x-text="surat.number + ' - ' + surat.title"
                                ></option>
                            </template>
                        </select>

                        @error('quran_surat_to_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ending Ayah --}}
                    <div>
                        <label for="aya_to" class="block text-gray-700 font-medium mb-2">
                            إلى الآية
                        </label>

                        <select
                            name="aya_to"
                            id="aya_to"
                            x-model="selectedAyaTo"
                            :disabled="loadingTo || !selectedSuratTo || !selectedAyaFrom"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                                   disabled:bg-gray-100 disabled:text-gray-400
                                   focus:outline-none focus:border-green-500
                                   focus:ring-1 focus:ring-green-500"
                            required
                        >
                            <option value="">اختر الآية الأخيرة</option>

                            <template
                                x-for="aya in availableToAyas"
                                :key="aya.id"
                            >
                                <option
                                    :value="String(aya.number)"
                                    x-text="'الآية ' + aya.number"
                                ></option>
                            </template>
                        </select>

                        @error('aya_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Loading --}}
                    <div
                        x-show="loadingFrom || loadingTo"
                        x-cloak
                        class="flex items-center gap-2 text-gray-500 text-sm"
                    >
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

                    {{-- Selected Quran Preview --}}
                    <template
                        x-if="selectedSurat && selectedSuratTo && selectedAyaFrom && selectedAyaTo"
                    >
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                            <div class="text-sm text-emerald-700 font-medium mb-2">
                                نطاق الآيات المحدد
                            </div>

                            <div class="text-gray-700 space-y-1">
                                <div>
                                    من سورة
                                    <span
                                        class="font-bold"
                                        x-text="suratName(selectedSurat)"
                                    ></span>
                                    - الآية
                                    <span
                                        class="font-bold"
                                        x-text="selectedAyaFrom"
                                    ></span>
                                </div>

                                <div>
                                    إلى سورة
                                    <span
                                        class="font-bold"
                                        x-text="suratName(selectedSuratTo)"
                                    ></span>
                                    - الآية
                                    <span
                                        class="font-bold"
                                        x-text="selectedAyaTo"
                                    ></span>
                                </div>
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
                            :disabled="
                                loadingFrom ||
                                loadingTo ||
                                !selectedSurat ||
                                !selectedSuratTo ||
                                !selectedAyaFrom ||
                                !selectedAyaTo
                            "
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
                surats: @js($surats->map(fn ($surat) => [
                    'id' => (string) $surat->id,
                    'number' => (int) $surat->number,
                    'title' => $surat->title,
                ])->values()),

                selectedSurat: @js((string) old('quran_surat_id', $question->quran_surat_id)),

                selectedSuratTo: @js((string) old(
                    'quran_surat_to_id',
                    $question->quran_surat_to_id ?? $question->quran_surat_id
                )),

                selectedAyaFrom: @js((string) old('aya_from', $question->aya_from)),

                selectedAyaTo: @js((string) old('aya_to', $question->aya_to)),

                ayasFrom: [],
                ayasTo: [],

                loadingFrom: false,
                loadingTo: false,

                fromRequestId: 0,
                toRequestId: 0,

                async init() {
                    if (!this.selectedSurat) {
                        return;
                    }

                    // Load starting Surah ayahs.
                    await this.loadAyas('from', false);

                    // Load ending Surah ayahs.
                    if (this.selectedSuratTo) {
                        if (this.selectedSurat === this.selectedSuratTo) {
                            this.ayasTo = this.ayasFrom;
                        } else {
                            await this.loadAyas('to', false);
                        }
                    }
                },

                get availableEndSurats() {
                    const startSurat = this.surats.find(
                        surat => surat.id === String(this.selectedSurat)
                    );

                    if (!startSurat) {
                        return [];
                    }

                    return this.surats.filter(
                        surat => surat.number >= startSurat.number
                    );
                },

                get availableToAyas() {
                    if (!this.selectedAyaFrom) {
                        return [];
                    }

                    // If both Surahs are the same, the ending
                    // Ayah cannot be before the starting Ayah.
                    if (this.selectedSurat === this.selectedSuratTo) {
                        return this.ayasTo.filter(
                            aya => Number(aya.number) >= Number(this.selectedAyaFrom)
                        );
                    }

                    // If the ending Surah is different, all its Ayahs are available.
                    return this.ayasTo;
                },

                suratName(id) {
                    const surat = this.surats.find(
                        surat => surat.id === String(id)
                    );

                    return surat ? surat.title : '';
                },

                async startSuratChanged() {
                    // Reset the Ayah selections.
                    this.selectedAyaFrom = '';
                    this.selectedAyaTo = '';

                    this.ayasFrom = [];
                    this.ayasTo = [];

                    const startSurat = this.surats.find(
                        surat => surat.id === String(this.selectedSurat)
                    );

                    const endSurat = this.surats.find(
                        surat => surat.id === String(this.selectedSuratTo)
                    );

                    if (!startSurat) {
                        this.selectedSuratTo = '';
                        return;
                    }

                    // The ending Surah cannot precede the starting Surah.
                    if (endSurat && endSurat.number < startSurat.number) {
                        this.selectedSuratTo = '';
                    }

                    await this.loadAyas('from', false);

                    if (!this.selectedSuratTo) {
                        return;
                    }

                    // If both Surahs are the same, reuse the loaded Ayahs.
                    if (this.selectedSurat === this.selectedSuratTo) {
                        this.ayasTo = this.ayasFrom;
                    } else {
                        // Important: reload the ending Surah if it is still valid.
                        await this.loadAyas('to', false);
                    }
                },

                async loadAyas(type, resetValues = true) {
                    const isFrom = type === 'from';

                    const suratId = isFrom
                        ? this.selectedSurat
                        : this.selectedSuratTo;

                    if (resetValues) {
                        if (isFrom) {
                            this.selectedAyaFrom = '';
                            this.selectedAyaTo = '';
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

                    // If both selections refer to the same Surah,
                    // there is no need for a second HTTP request.
                    if (
                        !isFrom &&
                        this.selectedSurat === this.selectedSuratTo &&
                        this.ayasFrom.length
                    ) {
                        this.ayasTo = this.ayasFrom;
                        return;
                    }

                    const requestId = isFrom
                        ? ++this.fromRequestId
                        : ++this.toRequestId;

                    if (isFrom) {
                        this.loadingFrom = true;
                        this.ayasFrom = [];
                    } else {
                        this.loadingTo = true;
                        this.ayasTo = [];
                    }

                    try {
                        const url = @json(
                            route('quran.surats.ayas', [
                                'quranSurat' => '__SURAT__',
                            ])
                        ).replace('__SURAT__', suratId);

                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Failed to load ayahs');
                        }

                        const data = await response.json();

                        // Ignore outdated responses if the selection changed.
                        if (
                            isFrom &&
                            requestId === this.fromRequestId &&
                            this.selectedSurat === suratId
                        ) {
                            this.ayasFrom = data;
                        } else if (
                            !isFrom &&
                            requestId === this.toRequestId &&
                            this.selectedSuratTo === suratId
                        ) {
                            this.ayasTo = data;
                        }

                    } catch (error) {
                        console.error(error);

                        if (isFrom && requestId === this.fromRequestId) {
                            this.ayasFrom = [];
                        } else if (!isFrom && requestId === this.toRequestId) {
                            this.ayasTo = [];
                        }

                        alert('تعذر تحميل الآيات. يرجى المحاولة مرة أخرى.');

                    } finally {
                        if (isFrom && requestId === this.fromRequestId) {
                            this.loadingFrom = false;
                        } else if (!isFrom && requestId === this.toRequestId) {
                            this.loadingTo = false;
                        }
                    }
                },

                validateAyaRange() {
                    if (!this.selectedAyaFrom) {
                        this.selectedAyaTo = '';
                        return;
                    }

                    if (
                        this.selectedSurat === this.selectedSuratTo &&
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
