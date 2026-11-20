<x-app-layout>
    <div class="space-y-6" x-data="{ open: @json($errors->any()) }" x-cloak>

        {{-- Architectural Header --}}
        <div class="page-header-brand bg-islamic-pattern-dark">
            <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-brand-gold-400/20 border border-brand-gold-400/40 flex items-center justify-center text-brand-gold-300">
                        <x-svgicon.brand-icon name="stages" class="w-6 h-6" />
                    </div>
                    <div>
                        <h1 class="font-display text-2xl font-bold text-white">إدارة التصفيات والمراحل</h1>
                        <p class="text-xs text-brand-teal-100/80 mt-0.5">تحديد المرحلة النشطة للمسابقة في جميع اللجان والمراكز</p>
                    </div>
                </div>
                <x-svgicon.rosette class="w-8 h-8 hidden sm:block" />
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card-brand overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-center">
                    <thead>
                        <tr>
                            <th class="py-3.5 px-4 text-xs font-bold">#</th>
                            <th class="py-3.5 px-4 text-sm font-bold text-right">اسم التصفية / المرحلة</th>
                            <th class="py-3.5 px-4 text-sm font-bold">الحالة</th>
                            <th class="py-3.5 px-4 text-sm font-bold w-40">تحكم</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-sand-100 text-sm">
                        @foreach ($stages as $stage)
                            <tr class="{{ $stage->active ? 'bg-brand-teal-50/70' : '' }} transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-brand-teal-800">{{ $stage->id }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-brand-teal-950">{{ $stage->title }}</td>
                                <td class="py-3.5 px-4">
                                    @if ($stage->active)
                                        <span class="badge-teal">
                                            <span class="w-2 h-2 rounded-full bg-brand-teal-600"></span>
                                            نشط حالياً
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                            غير نشط
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <form action="{{ route('stage.toggle', $stage->id) }}" method="POST">
                                        @csrf
                                        @if (!$stage->active)
                                            <button class="btn-brand-primary w-full py-2 text-xs">
                                                تفعيل المرحلة
                                            </button>
                                        @else
                                            <span class="text-xs font-semibold text-brand-gold-700">المرحلة المعتمدة</span>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal --}}
        <div x-show="open" class="fixed inset-0 bg-brand-teal-950/60 backdrop-blur-xs flex items-center justify-center z-50 p-4"
            x-transition.opacity>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-brand-sand-200 p-6" @click.away="open = false" x-transition>
                <h3 class="text-lg font-bold mb-4 text-center text-brand-teal-900">إضافة التصفيات</h3>

                <form method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1.5 text-sm font-bold text-brand-teal-900">اسم التصفيات</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border rounded-xl px-3.5 py-2.5 text-sm" required>
                    </div>

                    <div class="flex justify-end gap-2.5 mt-5">
                        <button type="button" @click="open = false" class="btn-brand-outline">
                            إلغاء
                        </button>
                        <button type="submit" class="btn-brand-primary">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
