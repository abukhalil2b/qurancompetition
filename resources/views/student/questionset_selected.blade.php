<x-app-layout>

    <div class="p-6 max-w-7xl mx-auto">

        {{-- Main Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            {{-- Header --}}
            <div class="p-6 bg-gray-50 border-b flex justify-between items-center">

                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">
                        لوحة تقييم المتسابق
                    </h1>

                    <p class="text-indigo-700 font-semibold mt-1">
                        {{ $student->name }}
                    </p>
                </div>

                @php
                    $done = $studentQuestionSelections->every->done;
                @endphp

                {{-- Overall Status --}}
                <span
                    class="px-4 py-1 rounded-full text-sm font-semibold
                    {{ $done ? 'bg-green-600 text-white' : 'bg-yellow-100 text-yellow-700' }}">

                    {{ $done ? 'اكتمل التقييم' : 'بانتظار المحكمين' }}

                </span>
                @if ($done)
                    <a class="font-bold" href="{{ route('result.show', $competition->id) }}">عرض النتيجة</a>
                @endif
            </div>


            {{-- Question Set Info --}}
            <div class="p-6 border-b bg-white">

                <h3 class="text-xl font-bold text-indigo-800">
                    {{ $questionset->title }}
                </h3>

                <p class="text-sm text-gray-600 mt-1">
                    المستوى: {{ $questionset->level }}
                    —
                    عدد الأسئلة: {{ $studentQuestionSelections->count() }}
                </p>

            </div>


            {{-- Questions Grid --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                @forelse ($studentQuestionSelections as $selection)
                    @php
                        $question = $selection->question;
                    @endphp

                    <a href="{{ route('memorization.start', $selection->id) }}"
                        class="block p-5 border-2 rounded-2xl shadow-sm
                       transition-all duration-200

                       {{ $selection->done
                           ? 'border-green-500 bg-green-50 hover:ring-2 hover:ring-green-400'
                           : 'border-gray-200 bg-white hover:shadow-md' }}">

                        {{-- Question Header --}}
                        <div class="flex items-start gap-4 mb-5">

                            {{-- Position --}}
                            <span
                                class="flex-shrink-0 w-11 h-11 rounded-xl
                                flex items-center justify-center
                                font-bold text-white text-lg

                                {{ $selection->done ? 'bg-green-600' : 'bg-indigo-600' }}">

                                {{ $selection->position }}

                            </span>


                            {{-- Question Information --}}
                            <div class="min-w-0 flex-1">

                                {{-- Question Number --}}
                                <h4 class="font-bold text-gray-900 text-lg">
                                    السؤال {{ $selection->position }}
                                </h4>

                                {{-- Surah --}}
                                <div class="mt-2 flex items-center gap-2">

                                    <span class="text-gray-500">
                                        السورة:
                                    </span>

                                    <span class="font-bold text-indigo-700">
                                        {{ $question?->surat?->title ?? 'غير محددة' }}
                                    </span>

                                </div>


                                {{-- Ayah Range --}}
                                <div class="mt-2 flex items-center gap-2">

                                    <span class="text-gray-500">
                                        من الآية:
                                    </span>

                                    <span class="font-bold text-gray-900">
                                        {{ $question?->aya_from ?? '-' }}
                                    </span>

                                    <span class="text-gray-400">
                                        إلى
                                    </span>

                                    <span class="font-bold text-gray-900">
                                        {{ $question?->aya_to ?? '-' }}
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Quran Information --}}
                        <div class="border-t pt-4 mb-4">


                            <div class="flex flex-wrap gap-2 text-sm">

                                {{-- Riwaya --}}
                                <span
                                    class="inline-flex items-center
                                             px-3 py-1.5 rounded-lg
                                             bg-indigo-50 text-indigo-700
                                             font-semibold">

                                    الرواية:
                                    <span class="mr-1">
                                        {{ $question?->riwaya ?? '-' }}
                                    </span>

                                </span>


                                {{-- Juz --}}
                                <span
                                    class="inline-flex items-center
                                             px-3 py-1.5 rounded-lg
                                             bg-gray-100 text-gray-700
                                             font-semibold">

                                    الجزء:
                                    <span class="mr-1">
                                        {{ $question?->juz ?? '-' }}
                                    </span>

                                </span>

                            </div>

                        </div>


                        {{-- Footer --}}
                        <div
                            class="flex justify-between items-center
                                    border-t pt-3 text-sm">

                            @if ($selection->done)
                                <span
                                    class="flex items-center gap-2
                                             text-green-700 font-semibold">

                                    <i class="fas fa-check-circle"></i>

                                    مكتمل

                                    <span class="text-gray-500">
                                        — إجمالي الخصم:
                                        {{ $selection->totalDeduction() }}
                                    </span>

                                </span>
                            @else
                                <span
                                    class="flex items-center gap-2
                                             text-gray-400">

                                    <i class="fas fa-clock"></i>

                                    بانتظار التقييم

                                </span>
                            @endif


                            {{-- Arrow --}}
                            <svg class="w-5 h-5
                                {{ $selection->done ? 'text-green-600' : 'text-gray-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />

                            </svg>

                        </div>

                    </a>

                @empty

                    <div class="col-span-full text-center py-12">

                        <div class="text-gray-400 text-5xl mb-4">
                            ?
                        </div>

                        <p class="text-gray-500">
                            لا توجد أسئلة محددة لهذه الباقة.
                        </p>

                    </div>
                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>
