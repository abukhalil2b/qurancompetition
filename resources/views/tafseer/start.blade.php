<x-app-layout>
<div class="max-w-4xl mx-auto py-8">

    <h2 class="text-xl font-bold mb-6">
        تقييم التفسير – {{ $competition->student->name }}
    </h2>

    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('tafseer.store') }}" method="POST">
        @csrf

        @foreach ($questions as $question)
            <div class="mb-4 p-3 border rounded">
                <p class="font-semibold">{{ $question->order }}. {{ $question->content }}</p>

                {{-- إدخال الدرجة --}}
                <input type="hidden" name="questions[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                <label class="block mt-1">
                    الدرجة (0 – {{ $question->weight }}):
                    <input type="number" name="questions[{{ $question->id }}][score]" value="0" min="0" max="{{ $question->weight }}" class="border rounded px-2 py-1 w-20">
                </label>

                {{-- ملاحظة المحكم --}}
                <label class="block mt-2">
                    ملاحظة:
                    <textarea name="questions[{{ $question->id }}][note]" class="w-full mt-1 p-1 border rounded" placeholder="اكتب ملاحظة"></textarea>
                </label>
            </div>
        @endforeach

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-semibold">
            حفظ التقييم
        </button>
    </form>

</div>
</x-app-layout>
