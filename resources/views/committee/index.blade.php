<x-app-layout>
    <div x-data="{
        openEditModal: false,
        currentCommittee: { id: null, title: '', gender: '', center_id: null, active: 0 },
        editUrl: '',
        openEdit(committee, url) {
            this.currentCommittee = {
                id: Number(committee.id),
                title: committee.title ?? '',
                gender: committee.gender ?? 'males',
                center_id: Number(committee.center_id),
                active: Number(committee.active),
            };
            this.editUrl = url;
            this.openEditModal = true;
        }
    }" class="p-4 md:p-6 max-w-7xl mx-auto" dir="rtl">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                إدارة اللجان . {{ $stage->title }}
            </h1>
            <p class="text-blue-700 bg-blue-100 p-2 rounded text-sm">
                رئيس اللجنة هو من يختار باقة الأسئلة للمتسابق، هو من يحدد السؤال الملغي
            </p>
        </div>

        {{-- Committees List --}}
        <div class="space-y-6">

            @forelse($committees as $centerCommittees)

                @php
                    $center = $centerCommittees->first()?->center;
                @endphp

                <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden">

                    {{-- Center Header --}}
                    <div class="px-5 py-4 bg-slate-100 border-b border-slate-200 grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-6 4h1m4 0h1" />
                            </svg>
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">
                                    {{ $center?->title ?? 'بدون مركز' }}
                                </h2>
                                <p class="py-1 text-xs text-gray-600"> {{ $center?->period }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $centerCommittees->count() }} لجنة
                                    @if ($center)
                                        • {{ $centerStudentCounts[$center->id] ?? 0 }} متسابق
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($center)
                            <div class="flex justify-end">
                                <a href="{{ route('competition.student.index', $center->id) }}"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg
                                          bg-emerald-50 text-emerald-700 border border-emerald-100
                                          hover:bg-emerald-100 hover:border-emerald-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold">المتسابقون</span>
                                    <span
                                        class="min-w-6 h-6 px-1.5 flex items-center justify-center rounded-full
                                                 bg-white text-emerald-700 text-xs font-bold border border-emerald-100">
                                        {{ $centerStudentCounts[$center->id] ?? 0 }}
                                    </span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Committees --}}
                    <div class="p-4 space-y-4">
                        @foreach ($centerCommittees as $committee)
                            <div
                                class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">

                                {{-- Committee Header --}}
                                <div
                                    class="p-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                                    <div>
                                        <h2 class="text-lg font-bold text-blue-900 flex flex-wrap items-center gap-2">
                                            <span>{{ $committee->title }}</span>

                                            <span class="text-xs font-normal text-gray-500">
                                                • {{ __($committee->gender) }}
                                            </span>

                                            @if ($committee->active)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    نشطة
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                    غير نشطة
                                                </span>
                                            @endif
                                        </h2>
                                    </div>

                                    {{-- Edit button --}}
                                    @php
                                        $committeeData = json_encode(
                                            $committee->only(['id', 'title', 'gender', 'center_id', 'active']),
                                            JSON_HEX_APOS | JSON_HEX_QUOT,
                                        );
                                    @endphp

                                    <button type="button" data-committee="{{ $committeeData }}"
                                        data-url="{{ route('committee.update', $committee->id) }}"
                                        @click="openEdit(JSON.parse($el.dataset.committee), $el.dataset.url)"
                                        class="text-gray-400 hover:text-blue-600 p-2 transition-colors"
                                        title="تعديل اللجنة">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Committee Users --}}
                                <div class="p-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @forelse($committee->users as $user)
                                            <div
                                                class="flex items-center justify-between p-2 rounded-lg border
                                                {{ $user->pivot->is_judge_leader ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-white' }}">

                                                {{-- User info --}}
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <a href="{{ route('user.show', $user->id) }}"
                                                        class="flex items-center gap-2 group min-w-0">
                                                        <div
                                                            class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center transition-colors
                                                            {{ $user->pivot->is_judge_leader
                                                                ? 'bg-amber-500 text-white'
                                                                : 'bg-gray-100 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600' }}">
                                                            @if ($user->pivot->is_judge_leader)
                                                                <svg class="w-4 h-4" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034c-.36-.261-.849-.261-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p
                                                                class="text-sm font-bold truncate transition-colors
                                                                {{ $user->pivot->is_judge_leader ? 'text-amber-900' : 'text-gray-700 group-hover:text-blue-700' }}">
                                                                {{ $user->name }}
                                                            </p>
                                                            <p class="text-[10px] text-gray-500">
                                                                {{ __($user->user_type) }}
                                                            </p>
                                                        </div>
                                                    </a>
                                                </div>

                                                {{-- Actions --}}
                                                <div class="flex items-center gap-1 flex-shrink-0">
                                                    @if ($user->user_type === 'judge')
                                                        @if (!$user->pivot->is_judge_leader)
                                                            <form
                                                                action="{{ route('committee.set-leader', $committee) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="user_id"
                                                                    value="{{ $user->id }}">
                                                                <button type="submit"
                                                                    class="text-[9px] bg-white border border-gray-200 px-2 py-1 rounded hover:bg-amber-500 hover:text-white transition-all whitespace-nowrap">
                                                                    تعيين رئيس
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span
                                                                class="text-[10px] font-bold text-amber-600 px-2 whitespace-nowrap">
                                                                رئيس اللجنة
                                                            </span>
                                                        @endif
                                                    @endif

                                                    <form
                                                        action="{{ route('committee.remove_user', [$committee, $user]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('هل أنت متأكد من إزالة هذا المستخدم من اللجنة؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 text-gray-300 hover:text-red-600 transition-colors"
                                                            title="حذف من اللجنة">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div
                                                class="col-span-full text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                                <p class="text-xs text-gray-400 italic">لا يوجد أعضاء حالياً</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-400">لا توجد لجان مضافة</p>
                    </div>
                @endforelse
            </div>

            {{-- Create Form — full width at bottom --}}
            <div class="mt-8">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-5">
                    <h2 class="text-lg font-bold mb-5 flex items-center gap-2 text-gray-800">
                        <span class="p-1.5 bg-blue-600 rounded-lg text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </span>
                        إضافة لجنة جديدة
                    </h2>

                    <form action="{{ route('committee.store') }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        @csrf
                        @include('committee._form_fields')
                        <button type="submit"
                            class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                            حفظ اللجنة
                        </button>
                    </form>
                </div>
            </div>

            {{-- Edit Modal --}}
            <div x-show="openEditModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full" @click.away="openEditModal = false">

                    <div class="p-6 border-b flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">تعديل اللجنة</h3>
                        <button type="button" @click="openEditModal = false"
                            class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>

                    <form :action="editUrl" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">اسم اللجنة</label>
                            <input type="text" name="title" x-model="currentCommittee.title"
                                class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Gender --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
                                <select name="gender" x-model="currentCommittee.gender"
                                    class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="males">ذكور</option>
                                    <option value="females">إناث</option>
                                </select>
                            </div>

                            {{-- Center --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">المركز</label>
                                <select name="center_id" x-model.number="currentCommittee.center_id"
                                    class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                    @foreach ($centers as $center)
                                        <option :value="{{ $center->id }}">{{ $center->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Active --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">حالة اللجنة</label>
                            <select name="active" x-model.number="currentCommittee.active"
                                class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                                <option :value="1">نشطة</option>
                                <option :value="0">غير نشطة</option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
                                تحديث البيانات
                            </button>
                            <button type="button" @click="openEditModal = false"
                                class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>
