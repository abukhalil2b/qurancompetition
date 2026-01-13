<x-app-layout>
<div class="max-w-4xl mx-auto py-8">

    <h2 class="text-xl font-bold mb-6">
        تقييم التفسير – {{ $competition->student->name }}
    </h2>

    <form action="{{ route('tafseer.store') }}" method="POST">
        @csrf
        
        {{-- Important: Needed for the redirect in your controller --}}
        <input type="hidden" name="competition_id" value="{{ $competition->id }}">

        @foreach ($questions as $question)
            {{-- Get existing evaluation for this question if it exists --}}
            @php
                $existing = $evaluations->get($question->id);
            @endphp

            <div class="mb-4 p-3 border rounded {{ $existing ? 'bg-green-50 border-green-200' : 'bg-white' }}">
                <p class="font-semibold">{{ $question->order }}. {{ $question->content }}</p>

                <input type="hidden" name="questions[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                
                <label class="block mt-1">
   الدرجة من 10
    <input type="number" 
           step="0.1" 
           name="questions[{{ $question->id }}][score]" 
           value="{{ old('questions.'.$question->id.'.score', $existing?->score ?? 0) }}" 
           min="0" 
           max="{{ $question->weight }}" 
           class="border rounded px-2 py-1 w-20 focus:ring-indigo-500 focus:border-indigo-500">
</label>
                <label class="block mt-2">
                    ملاحظة:
                    <textarea name="questions[{{ $question->id }}][note]" 
                              class="w-full mt-1 p-1 border rounded focus:ring-indigo-500 focus:border-indigo-500" 
                              placeholder="اكتب ملاحظة">{{ old('questions.'.$question->id.'.note', $existing?->note ?? '') }}</textarea>
                </label>
            </div>
        @endforeach

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold hover:bg-indigo-700 transition">
            حفظ التقييم
        </button>
    </form>

</div>
</x-app-layout>