<x-app-layout>
    <div class="p-6" x-data="{ open: @json($errors->any()) }" x-cloak>
        {{-- Header --}}

        {{-- Table --}}
        <table class="w-full text-center">
            <thead>
                <tr class="border-b">
                    <th class="py-2">#</th>
                    <th class="py-2">التصفيات</th>
                    <th class="py-2">نشط</th>
                    <th class="py-2">تحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stages as $stage)
                    <tr class="border-b  {{ $stage->active ? 'bg-green-100' : '' }}">
                        <td class="py-2">{{ $stage->id }}</td>
                        <td class="py-2">{{ $stage->title }}</td>
                        <td class="py-2">
                            @if ($stage->active)
                                <span class="text-green-600 font-bold">نعم</span>
                            @else
                                <span class="text-red-600 font-bold">لا</span>
                            @endif
                        </td>
                        <td class="py-2">

                            <form action="{{ route('stage.toggle', $stage->id) }}" method="POST">
                                @csrf

                                @if ($stage->active)
                                 
                                @else
                                    <button class="px-3 w-full py-1 bg-gray-600 text-white rounded">
                                        تفعيل
                                    </button>
                                @endif

                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        {{-- Modal --}}
        <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition.opacity>
            <div class="bg-white w-full max-w-md rounded-lg shadow p-6" @click.away="open = false" x-transition>
                <h3 class="text-lg font-bold mb-4 text-center">إضافة التصفيات</h3>

                <form method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="block mb-1">اسم التصفيات</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>


                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">
                            إلغاء
                        </button>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
