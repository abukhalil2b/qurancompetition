<x-app-layout>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">المراكز</h1>

        <div class="bg-white shadow rounded-lg p-4">
            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">#</th>
                        <th>اسم المركز</th>
                        <th>الوصف</th>
                        <th>عرض</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($centers as $center)
                        <tr class="border-b">
                            <td class="py-2">{{ $loop->iteration }}</td>
                            <td>{{ $center->title }}</td>
                            <td>{{ Str::limit($center->description, 50) }}</td>
                            <td>
                                <a href="{{ route('center.show', $center->id) }}"
                                   class="text-blue-600 hover:underline">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-gray-500">
                                لا توجد مراكز مسجلة حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
