<x-app-layout>
<div class="p-6 max-w-xl mx-auto bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">تعديل عنصر التقييم</h2>

    <form method="POST" action="{{ route('evaluation_element.update', $evaluationElement->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="block font-semibold mb-1">العنوان</label>
            <input type="text" name="title" 
                   value="{{ $evaluationElement->title }}"
                   class="w-full border rounded p-2">
        </div>
       

        <div class="mb-3">
            <label class="block font-semibold mb-1">الحد الأعلى للنقاط</label>
            <input type="number" name="max_score"
                   value="{{ $evaluationElement->max_score }}"
                   class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                تحديث
            </button>
        </div>
    </form>

</div>
</x-app-layout>
