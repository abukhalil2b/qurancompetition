<x-app-layout>
    <div class="p-4 md:p-6" dir="rtl">

        <h1 class="text-xl font-bold mb-6 text-gray-800">
            قائمة مستخدمي النظام
        </h1>

        {{-- Mobile --}}
        <div class="md:hidden space-y-4">

            @foreach ($users as $user)

                @php
                    $committeesByStage = $user->committees->groupBy('stage_id');
                @endphp

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">

                    <div class="flex justify-between items-start gap-3 mb-4">

                        <div class="min-w-0">
                            <div class="text-xs text-gray-400 mb-1">
                                #{{ $loop->iteration }}
                            </div>

                            <h3 class="font-bold text-lg text-gray-900 truncate">
                                {{ $user->name }}
                            </h3>
                        </div>

                        <span @class([
                            'shrink-0 px-2.5 py-1 text-xs rounded-full font-medium',
                            'bg-blue-100 text-blue-700' => $user->user_type === 'judge',
                            'bg-green-100 text-green-700' => $user->user_type === 'admin',
                            'bg-purple-100 text-purple-700' => $user->user_type === 'organizer',
                            'bg-gray-100 text-gray-700' => $user->user_type === 'student',
                        ])>
                            {{ __($user->user_type) }}
                        </span>

                    </div>

                    <div class="border-t border-gray-100 pt-3">

                        <p class="text-xs font-bold text-gray-400 mb-3">
                            اللجان حسب المرحلة
                        </p>

                        @forelse ($committeesByStage as $committees)

                            @php
                                $stage = $committees->first()->stage;
                            @endphp

                            <div class="mb-3 last:mb-0">

                                <div class="text-sm font-bold text-gray-700 mb-1">
                                    {{ $stage?->title ?? 'مرحلة غير محددة' }}
                                </div>

                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($committees as $committee)
                                        <span class="px-2 py-1 rounded-md bg-gray-100 text-xs text-gray-700">
                                            {{ $committee->title }}
                                        </span>
                                    @endforeach
                                </div>

                            </div>

                        @empty

                            <span class="text-xs text-gray-400 italic">
                                لا توجد لجان
                            </span>

                        @endforelse

                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100">

                        <a href="{{ route('user.show', $user->id) }}"
                            class="block text-center text-sm font-bold text-blue-600
                                   bg-blue-50 hover:bg-blue-100 py-2.5 rounded-lg transition">
                            عرض التفاصيل
                        </a>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto bg-white shadow-sm rounded-xl border border-gray-200">

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

                        @php
                            $committeesByStage = $user->committees->groupBy('stage_id');
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors">

                            <td class="px-4 py-4 text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-4 font-bold text-gray-900">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-4">

                                <span @class([
                                    'px-2.5 py-1 text-xs rounded-full',
                                    'bg-blue-100 text-blue-700' => $user->user_type === 'judge',
                                    'bg-green-100 text-green-700' => $user->user_type === 'admin',
                                    'bg-purple-100 text-purple-700' => $user->user_type === 'organizer',
                                    'bg-gray-100 text-gray-700' => $user->user_type === 'student',
                                ])>
                                    {{ __($user->user_type) }}
                                </span>

                            </td>

                            <td class="px-4 py-4">

                                @forelse ($committeesByStage as $committees)

                                    @php
                                        $stage = $committees->first()->stage;
                                    @endphp

                                    <div class="mb-2 last:mb-0">

                                        <div class="text-[11px] font-bold text-gray-500">
                                            {{ $stage?->title ?? 'مرحلة غير محددة' }}
                                        </div>

                                        <div class="text-xs text-gray-700">
                                            {{ $committees->pluck('title')->implode('، ') }}
                                        </div>

                                    </div>

                                @empty

                                    <span class="text-xs text-gray-400">
                                        لا توجد لجان
                                    </span>

                                @endforelse

                            </td>

                            <td class="px-4 py-4">

                                <a href="{{ route('user.show', $user->id) }}"
                                    class="inline-flex items-center px-3 py-1.5
                                           rounded-md bg-blue-50 text-blue-600
                                           hover:bg-blue-100 transition text-sm font-semibold">
                                    عرض
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</x-app-layout>