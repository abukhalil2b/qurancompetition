<x-app-layout>

    <div class="p-3 space-y-3">
        <h2 class="text-3xl font-semibold">{{ $center->title }}</h2>

        <div class="bg-white shadow rounded-lg p-4">
            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th>اللجنة</th>
                        <th>النوع/الجنس</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($center->committees as $committee)
                        <tr class="border-b">
                            <td>{{ $committee->title }}</td>
                            <td>{{ $committee->branch == 'male' ? 'ذكور' : 'إناث' }}</td>
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
