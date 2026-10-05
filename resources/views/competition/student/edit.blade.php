<x-app-layout>
    <div class="p-4 md:p-6 max-w-4xl mx-auto" dir="rtl">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">تعديل بيانات المتسابق</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $competition->student->name ?? '—' }}
                    •
                    {{ $competition->center->title ?? '—' }}
                    •
                    {{ $competition->stage->title ?? '—' }}
                </p>
            </div>

            <a href="{{ route('competition.student.index', $competition->center_id) }}"
               class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                رجوع للقائمة
            </a>
        </div>

        {{-- Flash / errors --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Read-only summary --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-6">
            <dl class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-slate-500">المرحلة</dt>
                    <dd class="font-semibold text-slate-800">{{ $competition->stage->title ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">باقة الأسئلة</dt>
                    <dd class="font-semibold text-slate-800">{{ $competition->questionset->title ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">المستوى</dt>
                    <dd class="font-semibold text-slate-800">{{ $competition->level }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">الترتيب</dt>
                    <dd class="font-semibold text-slate-800">{{ $competition->position }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">الدرجة النهائية</dt>
                    <dd class="font-semibold text-slate-800">{{ $competition->final_score ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Editable form --}}
        <form action="{{ route('competition.student.update', $competition) }}" method="POST"
              class="bg-white border border-gray-100 rounded-2xl shadow-sm">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-5">

                {{-- Committee (grouped by center) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        اللجنة <span class="text-gray-400 font-normal">(يتم تحديث المركز تلقائياً)</span>
                    </label>
                    <select name="committee_id" id="committee_id"
                            class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— بدون لجنة —</option>
                        @foreach ($committeesByCenter as $centerTitle => $group)
                            <optgroup label="{{ $centerTitle }}">
                                @foreach ($group as $committee)
                                    <option value="{{ $committee->id }}"
                                        @selected((int) old('committee_id', $competition->committee_id) === $committee->id)>
                                        {{ $committee->title }}
                                        ({{ __($committee->gender) }})
                                        {{ $committee->active ? '• نشطة' : '• غير نشطة' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                {{-- Student Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        حالة المتسابق
                    </label>
                    <select name="student_status"
                            class="w-full border-gray-200 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500">
                        @php
                            $statuses = [
                                'registration'         => 'مسجل',
                                'present'              => 'حاضر',
                                'with_committee'       => 'مع اللجنة',
                                'withdraw'             => 'منسحب',
                                'waiting_finalization' => 'بانتظار الإنهاء',
                                'finish_competition'   => 'أنهى المسابقة',
                            ];
                        @endphp
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}"
                                @selected(old('student_status', $competition->student_status) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('competition.student.index', $competition->center_id) }}"
                   class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-md shadow-blue-100">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</x-app-layout>