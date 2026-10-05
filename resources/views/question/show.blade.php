<x-app-layout>
<div class="min-h-screen bg-gray-50 p-4" dir="rtl">
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-sm text-gray-500 mb-2">
                        السؤال
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $question->questionset?->title }}
                    </h1>
                </div>
         
            </div>
        </div>

        {{-- Question Information --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">
                معلومات السؤال
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Riwaya --}}
                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-sm text-gray-500 mb-1">
                        الرواية
                    </div>

                    <div class="font-semibold text-gray-800">
                        {{ $question->riwaya }}
                    </div>

                </div>

                {{-- Juz --}}
                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-sm text-gray-500 mb-1">
                        الجزء
                    </div>

                    <div class="font-semibold text-gray-800">
                        الجزء {{ $question->juz }}
                    </div>

                </div>

                {{-- Surat --}}
                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-sm text-gray-500 mb-1">
                        السورة
                    </div>

                    <div class="font-semibold text-gray-800">

                        {{ $question->surat->number }}

                        -

                        {{ $question->surat->title }}

                    </div>

                </div>

                {{-- Aya range --}}
                <div class="bg-gray-50 rounded-xl p-4">

                    <div class="text-sm text-gray-500 mb-1">
                        الآيات
                    </div>

                    <div class="font-semibold text-gray-800">

                        {{ $question->aya_from }}

                        -

                        {{ $question->aya_to }}

                    </div>

                </div>
            </div>
        </div>

        {{-- Quran --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">


            {{-- Quran Header --}}
            <div class="px-6 py-5 border-b border-gray-100 text-center">

                <div class="text-sm text-gray-500 mb-2">
                    سورة
                </div>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $question->surat->title }}
                </h2>

                <div class="text-sm text-gray-500 mt-2">

                    الآيات
                    {{ $question->aya_from }}
                    -
                    {{ $question->aya_to }}

                </div>

            </div>


            {{-- Quran Text --}}
            <div class="p-6 sm:p-8">

                <div
                    class="text-2xl leading-[2.6] text-gray-800 text-justify"
                    style="font-family: 'Amiri', 'Traditional Arabic', serif;"
                >

                    @foreach ($ayas as $aya)

                        <span class="font-quran">
                            {{ $aya->content }}
                        </span>

                        <span
                            class="inline-flex items-center justify-center
                                   w-9 h-9 mx-1
                                   border border-gray-400
                                   rounded-full
                                   text-sm
                                   align-middle"
                        >
                            {{ $aya->number }}
                        </span>

                    @endforeach

                </div>

            </div>

        </div>

        {{-- Back --}}
        <div class="mt-6">
            <a href="{{ route('questionset.show', $question->questionset) }}"
                class="inline-flex items-center px-5 py-2.5
                       border border-gray-300
                       bg-white
                       text-gray-700
                       rounded-lg
                       hover:bg-gray-50
                       transition"
            >
                العودة إلى مجموعة الأسئلة
            </a>

        </div>
    </div>
</div>

</x-app-layout>
