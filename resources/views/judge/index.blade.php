<x-app-layout>

    <div class="p-6">

        <h1 class="text-xl font-bold mb-4">قائمة المحكمين</h1>
        <a href="{{ route('judge.create') }}">جديد</a>
        <table class="w-full text-right bg-white shadow rounded">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-2">#</th>
                    <th>الاسم</th>
                    <th>عرض</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($judges as $judge)
                    <tr class="border-b">
                        <td class="py-2 px-2">{{ $loop->iteration }}</td>
                        <td>{{ $judge->name }}</td>
                        <td>
                            <a href="{{ route('judge.show', $judge->id) }}" class="text-blue-600">عرض التفاصيل</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</x-app-layout>
