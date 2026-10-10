<x-app-layout>

    <div class="max-w-7xl mx-auto p-6 space-y-6">
        {{-- Registered students --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">

                <h2 class="font-bold text-slate-800">
                        المتسابقون المسجلون في {{ $stage->title }}. ({{ $competitions->count() }})
                </h2>

                <p class="text-xs text-slate-500 mt-1">
           {{ $center->title }} / {{ $center->period }}
                </p>

            </div>


            @if ($competitions->isEmpty())

                <div class="px-6 py-12 text-center text-sm text-slate-500">
                    لا يوجد متسابقون مسجلون حتى الآن.
                </div>
            @else
                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-right">

                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3 font-semibold">#</th>
                                <th class="px-6 py-3 font-semibold">المتسابق</th>
                                <th class="px-6 py-3 font-semibold">المستوى</th>
                                <th class="px-6 py-3 font-semibold">اللجنة</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @foreach ($competitions as $competition)
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">

                                    {{ $competition->student->name }}

                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">
                                            المستوى {{ $competition->level }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">

                                        @if ($competition->committee)
                                            <span class="text-slate-700">
                                                {{ $competition->committee->title }}
                                            </span>
                                        @else
                                            <span class="text-amber-600 text-xs">
                                                لم تحدد
                                            </span>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
