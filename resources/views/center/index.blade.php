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
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 border-b text-gray-600">
                {{ $loop->iteration }}
            </td>

            <td class="px-4 py-3 border-b font-medium text-gray-900">
                {{ $center->title }}
            </td>

            <td class="px-4 py-3 border-b">
                <div class="flex flex-col gap-2">
                    <span class="text-xs text-gray-600">
                        عدد اللجان:
                        <span class="font-semibold text-gray-800">
                            {{ $center->committees_count }}
                        </span>
                    </span>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('center.show', $center->id) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition">
                            قائمة المتسابقين
                        </a>

                        <a href="{{ route('center.statistic', $center->id) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition">
                            التقرير الإحصائي
                        </a>
                    </div>
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
