<x-app-layout>
    <div class="p-4 md:p-6" dir="rtl">

        <h1 class="text-xl font-bold mb-6 text-gray-800">قائمة مستخدمي النظام</h1>

        <div class="md:hidden space-y-4">
            @foreach ($users as $user)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">#{{ $loop->iteration }}</div>
                            <h3 class="font-bold text-lg text-gray-900">{{ $user->name }}</h3>
                        </div>
                        <span @class([
                            'px-2 py-1 text-xs rounded-md font-medium',
                            'bg-blue-100 text-blue-700' => $user->user_type === 'judge',
                            'bg-green-100 text-green-700' => $user->user_type === 'admin',
                            'bg-purple-100 text-purple-700' => $user->user_type === 'organizer',
                            'bg-gray-100 text-gray-700' => $user->user_type === 'student',
                        ])>
                            {{ __($user->user_type) }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-xs font-bold text-gray-400 mb-2">اللجان حسب المرحلة:</p>
                        @php
                            $committeesByStage = $user->committees->groupBy(fn ($c) => $c->pivot->stage_id);
                        @endphp

                        @forelse ($committeesByStage as $stageId => $committees)
                            @php $stage = \App\Models\Stage::find($stageId); @endphp
                            <div class="mb-2">
                                <div class="text-sm font-semibold text-gray-700">{{ $stage?->title }}</div>
                                <ul class="text-sm text-gray-600 list-disc list-inside mr-2">
                                    @foreach ($committees as $committee)
                                        <li>{{ $committee->title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <span class="text-xs text-gray-400 italic">لا توجد لجان</span>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('user.show', $user->id) }}" class="block text-center text-sm font-bold text-blue-600 bg-blue-50 py-2 rounded-md">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="hidden md:block overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-50 text-sm text-gray-600">
                    <tr>
                        <th class="px-4 py-4 border-b">#</th>
                        <th class="px-4 py-4 border-b">الاسم</th>
                        <th class="px-4 py-4 border-b">نوع المستخدم</th>
                        <th class="px-4 py-4 border-b">اللجان</th>
                        <th class="px-4 py-4 border-b">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-4">
                                <span @class([
                                    'px-2 py-0.5 text-xs rounded-full',
                                    'bg-blue-100 text-blue-700' => $user->user_type === 'judge',
                                    'bg-green-100 text-green-700' => $user->user_type === 'admin',
                                    'bg-purple-100 text-purple-700' => $user->user_type === 'organizer',
                                    'bg-gray-100 text-gray-700' => $user->user_type === 'student',
                                ])>
                                    {{ __($user->user_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php $groups = $user->committees->groupBy(fn ($c) => $c->pivot->stage_id); @endphp
                                @foreach ($groups as $sId => $comms)
                                    <div class="text-[11px] font-bold text-gray-500">{{ \App\Models\Stage::find($sId)?->title }}</div>
                                    <div class="text-xs text-gray-700 mb-1 italic">{{ $comms->pluck('title')->implode('، ') }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('user.show', $user->id) }}" class="text-blue-600 hover:underline">عرض</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>