<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Main Content Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

            {{-- Card Header: Student and Competition Details --}}
            <div class="p-6 sm:p-8 border-b border-gray-200 bg-gray-50 flex justify-between items-center">

                {{-- Student Name and Title --}}
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-1">
                        لوحة تقييم المتسابق
                    </h1>
                    <p class="text-xl text-indigo-700 font-semibold">
                        {{ $student->name }}
                    </p>
                </div>

                {{-- Evaluation Summary (Optional: Add a badge for overall status) --}}
                <div class="hidden sm:block">
                    <span
                        class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-sm font-medium text-blue-800">
                        <svg class="h-2 w-2 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        في انتظار التقييم
                    </span>
                </div>

            </div>

            {{-- Question Set Details Card --}}
            <div class="p-6 sm:p-8">
                <div class="border border-indigo-200 bg-indigo-50 rounded-xl p-5 shadow-inner">

                    <h3 class="text-xl font-bold text-indigo-800 mb-1">
                        {{ $questionset->title }}
                    </h3>

                    <div class="flex flex-col sm:flex-row sm:space-x-8 sm:space-x-reverse text-sm text-gray-700 mt-2">
                        <span class="mb-1 sm:mb-0">
                            <span class="font-medium text-indigo-600">المستوى:</span> {{ $questionset->level }}
                        </span>
                        <span class="mb-1 sm:mb-0">
                            <span class="font-medium text-indigo-600">عدد الأسئلة:</span>
                            {{ count($studentQuestionSelections) }}
                        </span>
                    </div>
                </div>

                {{-- Questions List Section --}}
                <h4 class="text-lg font-bold text-gray-800 mt-8 mb-4 border-b pb-2">
                    قائمة الأسئلة
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($studentQuestionSelections as $index => $studentQuestionSelection)
                        @php
                            $is_complete = $studentQuestionSelection->unique_judge_count == $evaluationElementCount;

                            // Refined color palette
                            $cardClasses = $is_complete
                                ? 'border-green-200 bg-green-50/50 ring-1 ring-green-100'
                                : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-lg';

                            $badgeClasses = $is_complete ? 'bg-green-600' : 'bg-indigo-600';
                            $titleClasses = $is_complete ? 'text-green-900' : 'text-gray-900';
                        @endphp

                        <div class="relative group">
                            {{-- Main Question Card --}}
                            <a href="{{ route('student.start_evaluation', $studentQuestionSelection->id) }}"
                                class="flex flex-col h-full p-5 rounded-2xl border-2 shadow-sm transition-all duration-300 {{ $cardClasses }}">

                                <div class="flex items-start mb-4">
                                    {{-- Index Badge --}}
                                    <span
                                        class="flex-shrink-0 w-10 h-10 rounded-xl {{ $badgeClasses }} text-white flex items-center justify-center font-bold text-lg shadow-lg ml-4">
                                        {{ $index + 1 }}
                                    </span>

                                    <div class="flex-1">
                                        <h5 class="font-bold text-lg leading-tight {{ $titleClasses }}">
                                            {{ Str::limit($studentQuestionSelection->question->content, 80) }}
                                        </h5>

                                        <div class="flex items-center mt-3 space-x-4 space-x-reverse">
                                            {{-- Completion Progress --}}
                                            <div
                                                class="flex items-center px-2 py-1 rounded-md bg-white/60 border border-black/5">
                                                <span class="text-xs font-medium text-gray-500 ml-1">التقدم:</span>
                                                <span
                                                    class="text-sm font-bold {{ $is_complete ? 'text-green-600' : 'text-indigo-600' }}">
                                                    {{ $studentQuestionSelection->unique_judge_count }} /
                                                    {{ $evaluationElementCount }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Footer Info --}}
                                <div class="mt-auto pt-4 border-t border-black/5 flex justify-between items-center">
                                    <span
                                        class="text-sm font-medium {{ $is_complete ? 'text-green-700' : 'text-gray-400' }}">
                                        @if ($is_complete)
                                            <i class="fas fa-check-circle ml-1"></i> إجمالي:
                                            {{ number_format($studentQuestionSelection->total_element_evaluation, 1) }}
                                        @else
                                            <i class="fas fa-clock ml-1"></i> بانتظار التقييم
                                        @endif
                                    </span>

                                    {{-- Arrow Icon --}}
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </div>
                            </a>

                            @if ($is_complete && auth()->user()->isCommitteeLeader($stage->id))
                                <div class="absolute -top-1.5 -left-1.5">
                                    <form action="{{ route('selection.reset', $studentQuestionSelection->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('تنبيه: سيتم مسح درجات هذا السؤال تماماً. استمرار؟')"
                                            class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all duration-200 shadow-sm border border-red-100"
                                            title="تصفير الدرجة">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- Report Button: Always visible for review, but styled as a secondary action --}}
        <a href="{{ route('final_report', $competition->id) }}"
            class="mt-4 w-full px-6 py-3 bg-white text-indigo-600 border-2 border-indigo-600 font-bold rounded-2xl shadow-sm flex items-center justify-center gap-3 hover:bg-indigo-50 transition">
            عرض التقرير النهائي
        </a>

    </div>
</x-app-layout>
