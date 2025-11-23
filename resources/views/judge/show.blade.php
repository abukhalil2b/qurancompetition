<x-app-layout>

    <div class="p-6 space-y-6">

        {{-- Judge Info --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold">بيانات المحكم</h2>
            <p class="mt-2"><strong>الاسم:</strong> {{ $judge->name }}</p>
            <p><strong>البريد:</strong> {{ $judge->email }}</p>
        </div>


        {{-- Assign to Committee --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-3">ربط المحكم بلجنة</h2>

            <form action="{{ route('judge.assignCommittee') }}" method="POST" class="flex gap-4 items-end">
                @csrf

                <input type="hidden" name="judge_id" value="{{ $judge->id }}">

                <div class="flex-1">
                    <label class="font-semibold">اختر اللجنة</label>
                    <select name="committee_id" class="w-full border rounded p-2">
                        @foreach ($committees as $committee)
                            <option value="{{ $committee->id }}">
                                {{ $committee->title }} - ({{ $committee->center->title }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    ربط
                </button>
            </form>
        </div>


        {{-- Committees Assigned --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-3">اللجان المرتبط بها المحكم</h2>

            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">#</th>
                        <th>اللجنة</th>
                        <th>المركز</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($judge->committees as $committee)
                        <tr class="border-b">
                            <td class="py-2">{{ $loop->iteration }}</td>
                            <td>{{ $committee->title }}</td>
                            <td>{{ $committee->center->title }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-2">
                                لا يوجد ارتباطات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>
