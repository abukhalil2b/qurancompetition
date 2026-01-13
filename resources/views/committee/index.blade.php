<x-app-layout>
    <div x-data="{
        openEditModal: false,
        currentCommittee: { title: '', gender: '', center_id: '' },
        editUrl: ''
    }" class="p-4 md:p-6 max-w-7xl mx-auto" dir="rtl">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">إدارة اللجان</h1>
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Right: Committees List --}}
            <div class="lg:col-span-2 space-y-4">
                @forelse($committees as $committee)
                    <div
                        class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-bold text-blue-900">{{ $committee->title }}</h2>
                                <span class="text-xs text-gray-500">{{ $committee->center->title }} •
                                    {{ __($committee->gender) }}</span>
                            </div>
                            <button
                                @click="
                                currentCommittee = {{ json_encode($committee) }};
                                editUrl = '{{ route('committee.update', $committee->id) }}';
                                openEditModal = true;
                            "
                                class="text-gray-400 hover:text-blue-600 p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse($committee->judges as $judge)
                                <div
                                    class="flex items-center justify-between p-2 rounded-lg border {{ $judge->pivot->is_judge_leader ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-white' }}">

                                    {{-- Right Side: Icon, Name, Role --}}
                                    <div class="flex items-center gap-2">
                                        {{-- Leader Gold Icon --}}
                                        @if ($judge->pivot->is_judge_leader)
                                            <div class="bg-amber-500 text-white p-1 rounded">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        @endif

                                        <div>
                                            <a href="{{ route('user.show', $judge->id) }}"
                                                class="flex items-center gap-3 group">

                                                {{-- User Icon / Avatar --}}
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors
            {{ $judge->pivot->is_judge_leader
                ? 'bg-amber-100 text-amber-600'
                : 'bg-gray-100 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>

                                                {{-- Text Details --}}
                                                <div>
                                                    <p
                                                        class="text-sm font-bold transition-colors
                {{ $judge->pivot->is_judge_leader ? 'text-amber-900' : 'text-gray-700 group-hover:text-blue-700' }}">
                                                        {{ $judge->name }}
                                                    </p>
                                                    <p class="text-[10px] text-gray-500">{{ __($judge->pivot->role) }}
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Left Side: Actions (Set Leader & Delete) --}}
                                    <div class="flex items-center gap-2">

                                        @if (!$judge->pivot->is_judge_leader)
                                            {{-- Action: Make Leader (Only for non-leaders) --}}
                                            <form action="{{ route('committee.set-leader', $committee) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $judge->id }}">
                                                <button type="submit"
                                                    class="text-[10px] bg-white border border-gray-200 px-2 py-1 rounded hover:bg-amber-500 hover:text-white transition-all whitespace-nowrap">
                                                    تعيين رئيس
                                                </button>
                                            </form>
                                        @else
                                            {{-- Status: Already Leader --}}
                                            <span
                                                class="text-[10px] font-bold text-amber-600 px-2 whitespace-nowrap">رئيس
                                                اللجنة</span>
                                        @endif

                                        {{-- Action: Remove Judge (For everyone) --}}
                                        <form action="{{ route('committee.remove-judge', [$committee, $judge]) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا المحكم من اللجنة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1 text-gray-400 hover:text-red-600 transition-colors"
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
                                <div class="col-span-1 sm:col-span-2 text-center py-4">
                                    <p class="text-sm text-gray-400 italic">لا يوجد أعضاء حالياً</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-400">لا توجد لجان مضافة</p>
                    </div>
                @endforelse
            </div>

            {{-- Left: Create Form --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-5 sticky top-6">
                    <h2 class="text-lg font-bold mb-5 flex items-center gap-2 text-gray-800">
                        <span class="p-1.5 bg-blue-600 rounded-lg text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </span>
                        إضافة لجنة جديدة
                    </h2>
                    <form action="{{ route('committee.store') }}" method="POST" class="space-y-4">
                        @csrf
                        @include('committee._form_fields')
                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100 mt-2">
                            حفظ اللجنة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="openEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full" @click.away="openEditModal = false">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">تعديل اللجنة</h3>
                    <button @click="openEditModal = false"
                        class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <form :action="editUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">اسم اللجنة</label>
                        <input type="text" name="title" x-model="currentCommittee.title"
                            class="w-full border-gray-200 rounded-lg p-2.5">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
                            <select name="gender" x-model="currentCommittee.gender"
                                class="w-full border-gray-200 rounded-lg p-2.5">
                                <option value="males">ذكور</option>
                                <option value="females">إناث</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">المركز</label>
                            <select name="center_id" x-model="currentCommittee.center_id"
                                class="w-full border-gray-200 rounded-lg p-2.5">
                                @foreach ($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700">تحديث
                            البيانات</button>
                        <button type="button" @click="openEditModal = false"
                            class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
