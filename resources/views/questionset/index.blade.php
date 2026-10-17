<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div class="flex items-center gap-4">

                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    باقات الأسئلة
                </h1>

                <span
                    class="text-sm md:text-base font-semibold
                           bg-green-100 text-green-700
                           px-4 py-2 rounded-lg
                           border border-green-200"
                >
                    {{ $level }}
                </span>

            </div>

            <div class="flex flex-wrap items-center gap-2">

                {{-- Create --}}
                <a
                    href="{{ route('questionset.create', $levelId) }}"
                    class="inline-flex items-center
                           px-5 py-3
                           bg-green-600 text-white
                           rounded-lg
                           hover:bg-green-700
                           transition shadow-sm"
                >
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء باقة جديدة
                </a>

                {{-- Print --}}
                <a
                    href="{{ route('questionset.print') }}"
                    class="inline-flex items-center
                           px-4 py-3
                           bg-yellow-500 text-white
                           rounded-lg
                           hover:bg-yellow-600
                           transition"
                >
                    <i class="fas fa-print ml-2"></i>
                    طباعة
                </a>

            </div>

        </div>


        {{-- Questionsets --}}
        @if ($questionsets->isNotEmpty())


{{-- Statistics --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

    {{-- Total --}}
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200
                bg-white p-5 shadow-sm">

        <div class="flex h-12 w-12 shrink-0 items-center justify-center
                    rounded-xl bg-blue-100 text-blue-700">
            <i class="fas fa-layer-group text-xl"></i>
        </div>

        <div>
            <p class="text-sm font-medium text-slate-500">
                إجمالي الباقات
            </p>

            <p class="mt-1 text-2xl font-bold text-slate-800">
                {{ $totalCount }}
            </p>
        </div>

    </div>

    {{-- Not selected --}}
    <div class="flex items-center gap-4 rounded-2xl border border-amber-200
                bg-amber-50 p-5 shadow-sm">

        <div class="flex h-12 w-12 shrink-0 items-center justify-center
                    rounded-xl bg-amber-100 text-amber-700">
            <i class="fas fa-exclamation-circle text-xl"></i>
        </div>

        <div>
            <p class="text-sm font-medium text-amber-800">
                المتبقي    
            </p>

            <p class="mt-1 text-2xl font-bold text-amber-900">
                {{ $notSelectedCount }}
            </p>
        </div>

    </div>

</div>

<div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

    @foreach ($questionsets as $set)

        <div
            class="group flex flex-col overflow-hidden rounded-2xl border bg-white
                   shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg border-blue-300 ring-1 ring-blue-100"
        >

            {{-- Card Header --}}
            <div class="flex-1 p-5">

                {{-- Status and question count --}}
                <div class="mb-4 flex items-center justify-between gap-2">

                    <span
                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1
                               text-sm font-bold tabular-nums bg-blue-50 text-blue-700"
                    >
                        {{ $set->questions_count }}
                        <span class="text-xs font-medium">/ 5</span>
                    </span>

                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold leading-8 text-slate-800
                           transition-colors group-hover:text-blue-700">
                    {{ $set->title }}
                </h3>

                {{-- Level --}}
                <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">

                    <i class="fas fa-layer-group text-slate-400"></i>

                    <span>{{ $level }}</span>

                </div>

                {{-- Selection status --}}
                @if ($set->selected)

                    <div class="mt-4 flex items-center gap-2 rounded-lg
                                border border-blue-100 bg-blue-50 px-3 py-2
                                text-sm font-medium text-blue-800">

                        <i class="fas fa-check-circle"></i>

                        <span>تم اختيار هذه الباقة</span>

                    </div>

                @endif

            </div>

            {{-- Card Footer --}}
            <div class="border-t border-slate-100 bg-slate-50/80 p-4">

                <div class="flex flex-wrap items-center gap-2">

                    {{-- Show --}}
                    <a
                        href="{{ route('questionset.show', $set) }}"
                        class="inline-flex flex-1 items-center justify-center gap-2
                               whitespace-nowrap rounded-lg bg-blue-600 px-3 py-2.5
                               text-sm font-semibold text-white
                               shadow-sm transition hover:bg-blue-700
                               focus:outline-none focus:ring-2 focus:ring-blue-500
                               focus:ring-offset-2"
                    >
                        إدارة الأسئلة
                    </a>

                    {{-- Edit --}}
                    <a
                        href="{{ route('questionset.edit', $set) }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg border border-amber-200 bg-amber-50
                               px-3 py-2.5 text-sm font-semibold text-amber-800
                               transition hover:border-amber-300 hover:bg-amber-100
                               focus:outline-none focus:ring-2 focus:ring-amber-500
                               focus:ring-offset-2"
                    >
                        تعديل
                    </a>

                    {{-- Delete --}}
                    <form
                        action="{{ route('questionset.destroy', $set) }}"
                        method="POST"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟');"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg border border-red-200 bg-white
                                   px-3 py-2.5 text-sm font-semibold text-red-600
                                   transition hover:border-red-300 hover:bg-red-50
                                   focus:outline-none focus:ring-2 focus:ring-red-500
                                   focus:ring-offset-2"
                        >
                            حذف
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

</div>

        @else

            {{-- Empty State --}}
            <div
                class="bg-white border border-gray-200
                       rounded-xl shadow-sm
                       text-center py-14 px-6"
            >

                <div
                    class="mx-auto w-20 h-20
                           bg-gray-100 rounded-full
                           flex items-center justify-center mb-5"
                >
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-700 mb-2">
                    لا توجد باقات أسئلة
                </h3>

                <p class="text-gray-500 mb-6">
                    ابدأ بإنشاء باقة أسئلة جديدة
                </p>

                <a
                    href="{{ route('questionset.create', $levelId) }}"
                    class="inline-flex items-center
                           px-5 py-3
                           bg-green-600 text-white
                           rounded-lg
                           hover:bg-green-700
                           transition shadow-sm"
                >
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء باقة جديدة
                </a>

            </div>

        @endif

    </div>

</x-app-layout>