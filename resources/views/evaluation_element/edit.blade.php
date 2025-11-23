<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
                تعديل عنصر التقييم
            </h1>

            <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-200" 
                 x-data="{ isChild: {{ $evaluationElement->parent_id ? 'true' : 'false' }} }">

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                        <ul class="list-disc mr-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('evaluation_element.update', $evaluationElement) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Title --}}
                        <div>
                            <label for="title" class="font-semibold mb-1 block">عنوان العنصر</label>
                            <input type="text" name="title" id="title" class="form-input w-full" required 
                                value="{{ old('title', $evaluationElement->title) }}">
                        </div>

                        {{-- Order --}}
                        <div>
                            <label for="order" class="font-semibold mb-1 block">ترتيب العنصر</label>
                            <input type="number" name="order" id="order" class="form-input w-full" required min="1"
                                value="{{ old('order', $evaluationElement->order) }}">
                        </div>

                    </div>

                    {{-- Parent Selector --}}
                    <div>
                        <label for="parent_id" class="font-semibold mb-1 block">العنصر الرئيسي (العنوان)</label>
                        <select name="parent_id" id="parent_id" class="form-select w-full" 
                                @change="isChild = $event.target.value != ''">
                            <option value="">-- لا يوجد (هذا عنوان رئيسي) --</option>
                            @foreach ($parentElements as $id => $title)
                                <option value="{{ $id }}" 
                                    {{ old('parent_id', $evaluationElement->parent_id) == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">إذا لم تحدد، سيكون هذا العنصر عنواناً رئيسياً.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Branch (Shown if not a child) --}}
                        <div x-show="!isChild">
                            <label for="branch" class="font-semibold mb-1 block">الفرع (للعناوين الرئيسية فقط)</label>
                            <input type="text" name="branch" id="branch" class="form-input w-full" 
                                :required="!isChild"
                                value="{{ old('branch', $evaluationElement->branch) }}">
                        </div>
                        
                        {{-- Max Score (Shown if it is a child) --}}
                        <div x-show="isChild">
                            <label for="max_score" class="font-semibold mb-1 block">الدرجة القصوى (للعناصر الفرعية فقط)</label>
                            <input type="number" name="max_score" id="max_score" class="form-input w-full" min="1"
                                :required="isChild"
                                value="{{ old('max_score', $evaluationElement->max_score) }}">
                        </div>

                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="{{ route('evaluation_element.index') }}"
                            class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                            رجوع
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>