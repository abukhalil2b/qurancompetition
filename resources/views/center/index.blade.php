<x-app-layout>
    <div class="p-6" dir="rtl">

        <!-- Page Title -->
        <h1 class="text-2xl font-bold mb-6">المراكز</h1>

        <div class="bg-white shadow rounded overflow-hidden">

            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-100 text-sm">
                    <tr>
                        <th class="px-4 py-3 border-b w-16">#</th>
                        <th class="px-4 py-3 border-b">اسم المركز</th>
                        <th class="px-4 py-3 border-b w-48">الإجراءات</th>
                    </tr>
                </thead>

                <tbody class="text-sm">
                    @forelse ($centers as $center)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 border-b text-gray-600">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3 border-b font-medium">
                                {{ $center->title }}
                            </td>

                            <td class="px-4 py-3 border-b">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-gray-600">
                                        عدد اللجان:
                                        <span class="font-semibold text-gray-800">
                                            {{ $center->committees_count }}
                                        </span>
                                    </span>

                                    <a href="{{ route('center.show', $center->id) }}"
                                       class="text-blue-600 hover:underline text-sm w-fit">
                                        عرض التفاصيل
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500 text-sm">
                                لا توجد مراكز مسجلة حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>
</x-app-layout>
