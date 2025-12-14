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
                <span class="inline-flex items-center rounded-full bg-blue-100 px-4 py-1.5 text-sm font-medium text-blue-800">
                    <svg class="h-2 w-2 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
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
                        <span class="font-medium text-indigo-600">عدد الأسئلة:</span> {{ count($studentQuestionSelections) }}
                    </span>
                </div>
            </div>

            {{-- Questions List Section --}}
            <h4 class="text-lg font-bold text-gray-800 mt-8 mb-4 border-b pb-2">
               قائمة الأسئلة 
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($studentQuestionSelections as $index => $studentQuestionSelection)
                    @php
                        // Determine status classes
                        $is_complete = $studentQuestionSelection->unique_judge_count == $evaluationElementCount;
                        $bg_class = $is_complete ? 'bg-green-50 hover:bg-green-100' : 'bg-white hover:bg-gray-50';
                        $border_class = $is_complete ? 'border-green-400 ring-4 ring-green-100' : 'border-gray-300';
                        $text_class = $is_complete ? 'text-green-800' : 'text-gray-800';
                        $score_text = $is_complete 
                            ? 'إجمالي النقاط: ' . $studentQuestionSelection->total_element_evaluation 
                            : 'في انتظار التقييم';
                    @endphp

                    {{-- Question Link Card --}}
                    <a href="{{ route('student.start_evaluation',$studentQuestionSelection->id) }}"
                        class="p-4 block rounded-xl shadow-md border-2 transition duration-150 ease-in-out {{ $bg_class }} {{ $border_class }}"
                    >
                        <div class="flex items-start">
                            {{-- Index Badge --}}
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3 {{ $is_complete ? 'bg-green-500' : 'bg-indigo-500' }}">
                                {{ $index + 1 }}
                            </div>
                            
                            <div>
                                {{-- Question Content --}}
                                <p class="font-semibold text-base {{ $text_class }} mb-1">
                                    {{ $studentQuestionSelection->question->content }}
                                </p>

                                {{-- Status/Score Indicator --}}
                                <div class="flex items-center text-sm mt-1">
                                    <span class="text-gray-600 font-medium">
                                        التقييمات المنجزة: 
                                    </span>
                                    <span class="ml-1 font-bold {{ $is_complete ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $studentQuestionSelection->unique_judge_count }} / {{ $evaluationElementCount }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $score_text }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>


</div>
</x-app-layout>
