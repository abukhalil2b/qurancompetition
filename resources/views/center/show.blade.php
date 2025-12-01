<x-app-layout>

<div class="p-3 space-y-3">


        <h2 class="text-3xl font-semibold">{{ $center->title }}</h2>
        <p class="text-gray-700 mt-2 leading-relaxed">
            {{ $center->description }}
        </p>


    {{-- Committees List --}}
    <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold mb-3"> اللجان التابعة للمركز {{ $center->title }}</h2>

        <table class="w-full text-right">
            <thead>
                <tr class="border-b">
                    <th class="py-2">#</th>
                    <th>اللجنة</th>
                    <th>الجنس</th>
                    <th>الفرع</th>
                </tr>
            </thead>

            <tbody>
                @forelse($center->committees as $committee)
                    <tr class="border-b">
                        <td class="py-2">{{ $loop->iteration }}</td>
                        <td>{{ $committee->title }}</td>
                        <td>{{ $committee->gender == 'male' ? 'ذكور' : 'إناث' }}</td>
                        <td>{{ $committee->branch ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-2 text-center text-gray-500">
                            لا توجد لجان مسجلة
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</x-app-layout>
