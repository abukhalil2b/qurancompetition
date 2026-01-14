<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم التفسير</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 antialiased font-sans">

    {{-- SUCCESS / WAITING STATE --}}
    @if($hasFinished)
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden text-center p-8">
                
                <div class="mx-auto bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>

                <h2 class="text-2xl font-black text-slate-800 mb-2">تم رصد الدرجات بنجاح</h2>
                <p class="text-slate-500 mb-8 leading-relaxed">
                    شكراً لك، تم حفظ تقييمك للطالب <span class="font-bold text-slate-700">{{ $competition->student->name }}</span>.
                    <br>
                    نحن بانتظار انتهاء باقي المحكمين لاعتماد النتيجة النهائية.
                </p>

                <a href="{{ route('tafseer.start', $competition->id) }}" 
                   class="block w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 transition-all active:scale-95 mb-4">
                   🔄 تحديث الحالة
                </a>
                
                <a href="{{ route('dashboard') }}" class="block w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold transition-all">
                    العودة للرئيسية
                </a>

            </div>
        </div>

    {{-- GRADING FORM STATE --}}
    @else
        <div class="max-w-6xl mx-auto py-6 px-4" x-data="tafseerBalance(@js($questions), @js($evaluations))">
            
            <form id="main-form" action="{{ route('tafseer.store') }}" method="POST">
                @csrf
                <input type="hidden" name="competition_id" value="{{ $competition->id }}">
                
                {{-- Sticky Header --}}
                <div class="sticky top-2 z-50 bg-white/95 backdrop-blur-sm border border-slate-200 rounded-2xl shadow-xl mb-8 p-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-600 p-3 rounded-xl text-white hidden sm:block">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-black text-slate-800 leading-tight">تقييم التفسير</h1>
                            <p class="text-sm text-indigo-600 font-bold">{{ $competition->student->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-center bg-slate-50 px-4 py-1 rounded-xl border border-slate-100">
                            <span class="block text-[10px] text-slate-400 font-bold">الإجمالي</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black text-slate-900" x-text="grandTotal()"></span>
                                <span class="text-xs font-bold text-slate-400">/ 40</span>
                            </div>
                        </div>
                        <button @click="document.getElementById('main-form').submit()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-black shadow-lg shadow-indigo-200 transition-all active:scale-95">
                            حفظ النتائج
                        </button>
                    </div>
                </div>

                {{-- Questions Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-20">
                    @foreach ($questions as $question)
                        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col gap-4">

                            {{-- Question Text --}}
                            <div class="flex gap-3">
                                <span class="bg-indigo-50 text-indigo-600 h-7 w-7 shrink-0 flex items-center justify-center rounded-lg text-xs font-black">
                                    {{ $question->order }}
                                </span>
                                <p class="text-slate-800 font-bold text-sm leading-snug">
                                    {{ $question->content }}
                                </p>
                            </div>

                            {{-- Control Panel --}}
                            <div class="flex items-center justify-between gap-2 bg-slate-50 p-3 rounded-2xl">
                                {{-- Deduction Side --}}
                                <div class="flex flex-1 gap-1">
                                    <button type="button" @click="deduct({{ $question->id }}, 1)"
                                        class="flex-1 h-12 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black text-lg shadow-[0_4px_0_rgb(153,27,27)] active:shadow-none active:translate-y-[4px] transition-all">
                                        -1
                                    </button>
                                    <button type="button" @click="deduct({{ $question->id }}, 0.5)"
                                        class="flex-1 h-12 bg-red-500 hover:bg-red-600 text-white rounded-xl font-black text-sm shadow-[0_4px_0_rgb(153,27,27)] active:shadow-none active:translate-y-[4px] transition-all">
                                        -0.5
                                    </button>
                                </div>

                                {{-- Score Display --}}
                                <div class="flex flex-col items-center justify-center bg-white border-2 border-indigo-100 rounded-2xl w-20 h-16 shrink-0">
                                    <span class="text-[9px] text-indigo-400 font-black uppercase">الدرجة</span>
                                    <span class="text-2xl font-black text-indigo-800" x-text="scores[{{ $question->id }}]"></span>
                                    <input type="hidden" name="questions[{{ $question->id }}][score]" :value="scores[{{ $question->id }}]">
                                    <input type="hidden" name="questions[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                                </div>

                                {{-- Addition Side --}}
                                <div class="flex flex-1 gap-1">
                                    <button type="button" @click="add({{ $question->id }}, 0.5)"
                                        class="flex-1 h-12 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black text-sm shadow-[0_4px_0_rgb(6,95,70)] active:shadow-none active:translate-y-[4px] transition-all">
                                        +0.5
                                    </button>
                                    <button type="button" @click="add({{ $question->id }}, 1)"
                                        class="flex-1 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-lg shadow-[0_4px_0_rgb(6,95,70)] active:shadow-none active:translate-y-[4px] transition-all">
                                        +1
                                    </button>
                                </div>
                            </div>

                            {{-- Note Area --}}
                            <input type="text" name="questions[{{ $question->id }}][note]"
                                x-model="notes[{{ $question->id }}]" placeholder="أضف ملاحظة..."
                                class="w-full px-4 py-2 bg-slate-50 border-transparent rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-200 transition-all">
                        </div>
                    @endforeach
                </div>
            </form>
        </div>

        <script>
            function tafseerBalance(questions, existingEvals) {
                return {
                    scores: {},
                    notes: {},
                    init() {
                        questions.forEach(q => {
                            if (existingEvals && existingEvals[q.id]) {
                                this.scores[q.id] = parseFloat(existingEvals[q.id].score).toFixed(1);
                                this.notes[q.id] = existingEvals[q.id].note || '';
                            } else {
                                this.scores[q.id] = (10.0).toFixed(1);
                                this.notes[q.id] = '';
                            }
                        });
                    },
                    deduct(id, val) {
                        let current = parseFloat(this.scores[id]);
                        let result = Math.max(0, current - val);
                        this.scores[id] = result.toFixed(1);
                    },
                    add(id, val) {
                        let current = parseFloat(this.scores[id]);
                        let result = Math.min(10, current + val);
                        this.scores[id] = result.toFixed(1);
                    },
                    grandTotal() {
                        let total = Object.values(this.scores).reduce((a, b) => a + parseFloat(b), 0);
                        return total.toFixed(1);
                    }
                }
            }
        </script>
    @endif

</body>
</html>