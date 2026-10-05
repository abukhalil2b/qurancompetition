<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">

        {{-- Header Card --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-green-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-emerald-800 mb-2">
                        {{ $questionset->title }}
                    </h1>
                    <p class="text-emerald-600">
                        عدد الأسئلة: {{ $questions->count() }}
                    </p>
                </div>

                <a href="{{ route('question.create', $questionset) }}"
                   class="inline-flex items-center gap-2 px-6 py-3
                          bg-gradient-to-r from-emerald-600 to-green-600
                          text-white font-medium rounded-xl
                          hover:from-emerald-700 hover:to-green-700
                          transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة سؤال جديد
                </a>
            </div>
        </div>

        @if ($questions->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto bg-emerald-100 rounded-full
                                flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-emerald-800 mb-3">
                        لا توجد أسئلة بعد
                    </h3>
                    <p class="text-emerald-600 mb-6">
                        ابدأ بإضافة أول سؤال إلى هذه المجموعة
                    </p>

                    <a href="{{ route('question.create', $questionset) }}"
                       class="inline-flex items-center gap-2 px-6 py-3
                              bg-gradient-to-r from-emerald-600 to-green-600
                              text-white font-medium rounded-xl
                              hover:from-emerald-700 hover:to-green-700
                              transition-all duration-200 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة سؤال جديد
                    </a>
                </div>
            </div>
        @else
            {{-- Questions Table --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-emerald-50">
                            <tr>
                                <th class="py-4 px-6 text-right text-emerald-800 font-semibold w-16">#</th>
                                <th class="py-4 px-6 text-right text-emerald-800 font-semibold">السورة والآيات</th>
                                <th class="py-4 px-6 text-right text-emerald-800 font-semibold">التفاصيل</th>
                                <th class="py-4 px-6 text-right text-emerald-800 font-semibold w-48">الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-emerald-100">
                            @foreach ($questions as $question)
                                <tr class="hover:bg-emerald-50/50 transition-colors duration-150">

                                    {{-- Number --}}
                                    <td class="py-4 px-6 text-emerald-700 font-medium text-center">
                                        <span class="bg-emerald-100 text-emerald-800 w-8 h-8
                                                     flex items-center justify-center rounded-full mx-auto">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- Surat + Ayas --}}
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-lg text-emerald-900">
                                                    سورة {{ $question->surat?->title ?? '—' }}
                                                </span>

                                                @if ($question->surat)
                                                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">
                                                        رقم {{ $question->surat->number }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="text-gray-700">
                                                من الآية
                                                <span class="font-bold text-emerald-700">{{ $question->aya_from }}</span>
                                                إلى الآية
                                                <span class="font-bold text-emerald-700">{{ $question->aya_to }}</span>
                                            </div>
                                        </div>
                                    </td>



                                    {{-- Details --}}
                                    <td class="py-4 px-6">
                                        <div class="space-y-2 text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-500">الرواية:</span>
                                                <span class="font-medium text-gray-800">{{ $question->riwaya }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-500">الجزء:</span>
                                                <span class="font-medium text-gray-800">{{ $question->juz }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-4 px-6">
                                        <div class="flex gap-2 justify-end">

                                            {{-- Show --}}
                                            <a href="{{ route('question.show', $question) }}"
                                               class="inline-flex items-center gap-1 px-4 py-2
                                                      bg-emerald-100 text-emerald-800 rounded-lg
                                                      hover:bg-emerald-200 transition-colors duration-200 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                عرض
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('question.edit', $question) }}"
                                               class="inline-flex items-center gap-1 px-4 py-2
                                                      bg-amber-100 text-amber-800 rounded-lg
                                                      hover:bg-amber-200 transition-colors duration-200 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                تعديل
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('question.destroy', $question) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-4 py-2
                                                               bg-red-100 text-red-800 rounded-lg
                                                               hover:bg-red-200 transition-colors duration-200 font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    حذف
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>

</x-app-layout>