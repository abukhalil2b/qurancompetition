<x-app-layout>

    <a class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700" href="{{ route('user.create') }}">جديد</a>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">قائمة مستخدم النظام</h1>
        <table class="w-full text-right bg-white shadow rounded">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-2">#</th>
                    <th>الاسم</th>
                    <th>نوع المستخدم</th>
                    <th>عرض</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="py-2 px-2">{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ __($user->user_type) }}</td>
                        <td>
                            <a href="{{ route('user.show', $user->id) }}" class="text-blue-600">عرض التفاصيل</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</x-app-layout>
