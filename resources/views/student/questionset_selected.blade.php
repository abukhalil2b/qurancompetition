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
                    <p class="text-indigo-700 font-semibold">
                        {{ $student->name }}
                    </p>
                </div>

                {{-- Overall Status --}}
                <span class="px-4 py-1 rounded-full text-sm font-semibold
                    {{ $studentQuestionSelections->every->done
                        ? 'bg-green-600 text-white'
                        : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $studentQuestionSelections->every->done
                        ? 'اكتمل التقييم'
                        : 'بانتظار المحكمين' }}
                </span>
            </div>

            {{-- Question Set Info --}}
            <div class="p-6 border-b bg-white">
                <h3 class="text-xl font-bold text-indigo-800">
                    {{ $questionset->title }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    المستوى: {{ $questionset->level }} —
                    عدد الأسئلة: {{ $studentQuestionSelections->count() }}
                </p>
            </div>

            {{-- Questions Grid --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($studentQuestionSelections as $selection)

                    <a href="{{ route('memorization.start', $selection->id) }}"
                       class="p-5 border-2 rounded-2xl shadow-sm transition-all duration-200
                       {{ $selection->done
                            ? 'border-green-500 bg-green-50 hover:ring-2 hover:ring-green-400'
                            : 'border-gray-200 bg-white hover:shadow-md' }}">

                        {{-- Question Header --}}
                        <div class="flex items-start gap-4 mb-4">
                            {{-- Index Badge --}}
                            <span
                                class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white
                                {{ $selection->done ? 'bg-green-600' : 'bg-indigo-600' }}">
                                {{ $selection->position  }}
                            </span>

                            {{-- Question Content --}}
                            <h4 class="font-bold text-gray-900 leading-snug">
                                {{ Str::limit($selection->question->content, 80) }}
                            </h4>
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-between items-center border-t pt-3 text-sm">

                            @if ($selection->done)
                                <span class="flex items-center gap-2 text-green-700 font-semibold">
                                    <i class="fas fa-check-circle"></i>
                                    مكتمل — إجمالي الخصم:
                                    {{ $selection->totalDeduction() }}
                                </span>
                            @else
                                <span class="flex items-center gap-2 text-gray-400">
                                    <i class="fas fa-clock"></i>
                                    بانتظار التقييم
                                </span>
                            @endif

                            <svg class="w-5 h-5
                                {{ $selection->done ? 'text-green-600' : 'text-gray-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                    </a>

                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
