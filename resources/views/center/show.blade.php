<x-app-layout>

    <div class="p-4 md:p-6 space-y-6" dir="rtl">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800">
                    {{ $center->title }}
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    لجان المركز
                </p>
            </div>

            <div class="flex items-center gap-2">

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5
                             rounded-full bg-emerald-50 text-emerald-700 text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $center->committees->where('active', true)->count() }} نشطة
                </span>

                <span class="inline-flex items-center px-3 py-1.5
                             rounded-full bg-slate-100 text-slate-600 text-sm font-semibold">
                    {{ $center->committees->count() }} لجنة
                </span>

            </div>

        </div>


        {{-- Committees --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">

                <h3 class="font-bold text-slate-800">
                    اللجان
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    حالة اللجان والمرحلة المرتبطة بكل لجنة
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-right">

                    <thead class="bg-white border-b border-slate-200">

                        <tr class="text-sm text-slate-500">

                            <th class="px-5 py-3 font-semibold">
                                اللجنة
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                المرحلة
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                النوع / الجنس
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                الحالة
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($center->committees as $committee)

                            <tr class="{{ $committee->active
                                ? 'hover:bg-emerald-50/40'
                                : 'bg-slate-50/50 opacity-75' }}
                                transition">

                                {{-- Committee --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg
                                            {{ $committee->active
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-slate-100 text-slate-400' }}
                                            flex items-center justify-center">

                                            <svg class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1" />
                                            </svg>

                                        </div>

                                        <div>
                                            <div class="font-bold text-slate-800">
                                                {{ $committee->title }}
                                            </div>

                                            <div class="text-xs text-slate-400 mt-0.5">
                                                لجنة رقم {{ $committee->id }}
                                            </div>
                                        </div>

                                    </div>

                                </td>


                                {{-- Stage --}}
                                <td class="px-5 py-4">

                                    @if ($committee->stage)

                                        <span class="inline-flex items-center px-2.5 py-1
                                                     rounded-md bg-blue-50 text-blue-700
                                                     text-xs font-semibold">
                                            {{ $committee->stage->title }}
                                        </span>

                                    @else

                                        <span class="text-xs text-slate-400">
                                            غير محددة
                                        </span>

                                    @endif

                                </td>


                                {{-- Gender --}}
                                <td class="px-5 py-4">

                                    <span class="text-sm text-slate-700">
                                        {{ __($committee->gender) }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @if ($committee->active)

                                        <span class="inline-flex items-center gap-1.5
                                                     px-2.5 py-1 rounded-full
                                                     bg-emerald-50 text-emerald-700
                                                     text-xs font-bold">

                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                                            نشطة
                                        </span>

                                    @else

                                        <span class="inline-flex items-center
                                                     px-2.5 py-1 rounded-full
                                                     bg-slate-100 text-slate-500
                                                     text-xs font-semibold">

                                            غير نشطة

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <svg class="w-10 h-10 text-slate-300 mb-3"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1" />
                                        </svg>

                                        <p class="text-sm font-semibold text-slate-500">
                                            لا توجد لجان مسجلة
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>