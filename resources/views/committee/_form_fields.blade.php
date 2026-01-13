<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">اسم اللجنة</label>
    <input type="text" name="title" id="field_title"
        class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
        placeholder="مثال: لجنة مسقط" required>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
        <select name="gender" id="field_gender" class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
             <option value="males">ذكور</option>
             <option value="females">إناث</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">المركز</label>
        <select name="center_id" id="field_center_id" class="w-full border-gray-200 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
            @foreach ($centers as $center)
                <option value="{{ $center->id }}">{{ $center->title }}</option>
            @endforeach
        </select>
    </div>
</div>