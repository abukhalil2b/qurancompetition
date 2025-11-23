<x-app-layout>

    <div class="p-6 space-y-6">

        <h1 class="text-2xl font-bold">اللجان</h1>
اللجنة لفرع الحفظ + الحفظ والتفسير
        {{-- Create Form --}}
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3">إضافة لجنة جديدة</h2>

            <form action="{{ route('committee.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="font-semibold">اسم اللجنة</label>
                    <input type="text" name="title" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="font-semibold">الجنس</label>
                    <select name="gender" class="w-full border rounded p-2">
                        <option value="male">ذكور</option>
                        <option value="female">إناث</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold">المركز</label>
                    <select name="center_id" class="w-full border rounded p-2" required>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 text-left">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">حفظ</button>
                </div>

            </form>
        </div>

        {{-- Committees List --}}
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3">قائمة اللجان</h2>

            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">#</th>
                        <th>اللجنة</th>
                        <th>الجنس</th>
                        <th>المستوى</th>
                        <th>المركز</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($committees as $committee)
                        <tr class="border-b">
                            <td class="py-2">{{ $loop->iteration }}</td>
                            <td>{{ $committee->title }}</td>
                            <td>{{ $committee->gender == 'male' ? 'ذكور' : 'إناث' }}</td>
                            <td>{{ $committee->level ?? '-' }}</td>
                            <td>{{ $committee->center->title }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-2 text-center text-gray-500">لا توجد لجان مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</x-app-layout>
