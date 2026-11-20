<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدليل الإرشادي المصوّر — مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #EAE4D8;
            color: #07312E;
            margin: 0;
            padding: 0;
        }

        .pdf-page {
            width: 210mm;
            height: 297mm;
            margin: 18px auto;
            background: #FAF8F4;
            position: relative;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(7, 49, 46, 0.18);
            display: flex;
            flex-direction: column;
        }

        .pdf-sheet-wrapper {
            width: 100%;
            overflow-x: auto;
            padding: 0 12px;
        }

        .pdf-page-content {
            flex: 1;
            padding: 10mm 13mm 9mm 13mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        .callout-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 9999px;
            background: #C9A66B;
            color: #07312E;
            font-size: 11px;
            font-weight: 800;
            box-shadow: 0 2px 6px rgba(7, 49, 46, 0.25);
            flex-shrink: 0;
        }

        .mockup-window {
            background: #FFFFFF;
            border: 1.5px solid #D8C7A8;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 22px -6px rgba(7, 49, 46, 0.14);
        }

        .mockup-topbar {
            background: #07312E;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #C9A66B;
        }

        @media print {
            body {
                background: #FFFFFF !important;
            }

            .no-print {
                display: none !important;
            }

            .pdf-sheet-wrapper {
                overflow: visible !important;
                padding: 0 !important;
            }

            .pdf-page {
                margin: 0 !important;
                box-shadow: none !important;
                width: 210mm !important;
                height: 297mm !important;
                page-break-after: always;
                break-after: page;
            }

            .pdf-page:last-of-type {
                page-break-after: auto;
                break-after: auto;
            }
        }
    </style>
</head>

<body class="antialiased">

    {{-- Floating Print / Export Toolbar (Hidden in PDF) --}}
    <div class="no-print sticky top-0 z-50 bg-brand-teal-950/95 backdrop-blur-md text-white border-b-2 border-brand-gold-400 shadow-xl"
        style="background-color: rgba(4, 31, 29, 0.96); border-bottom-color: #C9A66B;">
        <div class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <x-svgicon.rosette class="w-7 h-7 shrink-0" />
                <div>
                    <div class="font-bold text-sm sm:text-base text-white">الدليل الإرشادي المصوّر للنظام (جاهز للطباعة كـ PDF)</div>
                    <div class="text-[11px] text-brand-gold-200" style="color: #E8D7B0;">
                        لأفضل نتيجة عند الحفظ كـ PDF: اختر حجم الورق <strong class="text-white">A4</strong> وفعّل خيار <strong class="text-white">رسومات الخلفية (Background Graphics)</strong>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" class="btn-brand-gold py-2 px-4 text-sm">
                    <x-svgicon.brand-icon name="print" class="w-4 h-4" />
                    <span>طباعة / حفظ كـ PDF</span>
                </button>
                <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white/15 hover:bg-white/25 text-white border border-white/25 transition">
                    <span>العودة للنظام</span>
                </a>
            </div>
        </div>
    </div>

    <div class="pdf-sheet-wrapper">
    {{-- =======================================================================
         PAGE 1: LUXURY ARCHITECTURAL COVER PAGE
         ======================================================================= --}}
    <section class="pdf-page bg-brand-teal-950 text-white">
        {{-- Full-bleed Architectural Background --}}
        <img src="{{ asset('images/hero-arch.jpg') }}" alt="غلاف الدليل"
            class="absolute inset-0 w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-b from-brand-teal-950/75 via-brand-teal-900/20 to-brand-teal-950/85"></div>

        <div class="relative z-10 h-full flex flex-col justify-between p-[14mm]">
            {{-- Official Institutional Top Banner --}}
            <div class="flex items-center justify-between bg-brand-teal-950/75 border border-brand-gold-400/40 rounded-2xl p-3.5">
                <img src="{{ asset('bg.png') }}" alt="وزارة الأوقاف ومؤسسة الشيخ مستهيل"
                    class="h-12 w-auto object-contain rounded-lg">
                <div class="text-left">
                    <div class="text-[11px] font-bold text-brand-gold-300">الإصدار الثاني</div>
                    <div class="text-xs font-extrabold text-white">{{ date('Y') }} م</div>
                </div>
            </div>

            {{-- Center Cover Title & Gold Plaque --}}
            <div class="text-center my-auto px-4">
                <div class="inline-flex items-center justify-center bg-white/95 rounded-2xl px-6 py-3.5 shadow-2xl border-2 border-brand-gold-300 mb-7">
                    <img src="{{ asset('logo.png') }}" alt="شعار المسابقة" class="h-16 w-auto object-contain">
                </div>

                <div class="flex justify-center mb-6">
                    <div class="inline-flex items-center gap-3 px-7 py-3.5 rounded-2xl brand-gold-plaque text-brand-teal-950">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <span class="font-extrabold text-xl tracking-wide">
                            الدليل الإرشادي الشامل لاستخدام البرنامج
                        </span>
                        <x-svgicon.rosette class="w-7 h-7" />
                    </div>
                </div>

                <h1 class="font-display text-4xl font-bold leading-snug text-white drop-shadow-md mb-3">
                    نظام إدارة وتحكيم مسابقة مؤسسة الشيخ مستهيل<br>
                    بن أحمد بن علي المعشني الوقفية الخاصة
                </h1>
                <p class="text-lg font-bold text-brand-gold-200 mb-8">
                    للقرآن الكريم وقراءاته
                </p>

                {{-- Mini Table of Contents on Cover --}}
                <div class="max-w-lg mx-auto bg-brand-teal-950/75 backdrop-blur-md border border-brand-gold-400/35 rounded-2xl p-5 text-right">
                    <div class="text-xs font-extrabold text-brand-gold-300 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-gold-400"></span>
                        <span>محتويات الدليل</span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-xs text-brand-teal-50">
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>1. نظرة عامة وأدوار النظام</span>
                            <span class="text-brand-gold-300 font-bold">ص 2</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>2. الدخول ولوحة التحكم</span>
                            <span class="text-brand-gold-300 font-bold">ص 3</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>3. التصفيات والمراكز واللجان</span>
                            <span class="text-brand-gold-300 font-bold">ص 4</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>4. باقات الأسئلة والتقييم</span>
                            <span class="text-brand-gold-300 font-bold">ص 5</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>5. الحضور واختيار الباقة</span>
                            <span class="text-brand-gold-300 font-bold">ص 6</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-1">
                            <span>6. التحكيم الإلكتروني والنتائج</span>
                            <span class="text-brand-gold-300 font-bold">ص 7</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cover Bottom Bar --}}
            <div class="flex items-center justify-between text-xs text-brand-gold-200 border-t border-brand-gold-400/30 pt-3">
                <span>دليل موجه للإدارة، مشرفي الأسئلة، المنظمين، ولجان التحكيم</span>
                <span class="font-bold text-white">صفحة 1 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 2: SYSTEM OVERVIEW, WORKFLOW & USER ROLES
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            {{-- Page Header --}}
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-5">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم الأول</div>
                            <h2 class="font-display text-xl font-bold">نظرة عامة على النظام وأدوار المستخدمين</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Intro Box --}}
                <div class="bg-white rounded-2xl border border-brand-sand-200 p-3.5 mb-4 shadow-xs">
                    <p class="text-xs leading-relaxed text-brand-teal-900">
                        صُمّم هذا النظام الإلكتروني لإدارة جميع مراحل <strong>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</strong> بدقة وموثوقية عالية، بدءاً من إعداد التصفيات والمراكز وتشكيل لجان التحكيم، ومروراً بإعداد باقات الأسئلة القرآنية وتسجيل حضور المتسابقين، وانتهاءً بالتحكيم المباشر وحساب الدرجات تلقائياً وإصدار التقارير الإحصائية وملفات النتائج النهائية.
                    </p>
                </div>

                {{-- End-to-End Competition Workflow Diagram --}}
                <div class="mb-4">
                    <h3 class="text-sm font-extrabold text-brand-teal-900 mb-2.5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-gold-400"></span>
                        <span>دورة عمل المسابقة داخل النظام (من البداية إلى النتيجة)</span>
                    </h3>

                    <div class="grid grid-cols-5 gap-2">
                        @php
                            $steps = [
                                ['num' => '1', 'icon' => 'stages', 'title' => 'الإعداد الإداري', 'desc' => 'تفعيل التصفية وتوزيع المراكز واللجان'],
                                ['num' => '2', 'icon' => 'questions', 'title' => 'باقات الأسئلة', 'desc' => 'إعداد الأسئلة ومعايير التقييم'],
                                ['num' => '3', 'icon' => 'attendance', 'title' => 'تسجيل الحضور', 'desc' => 'تأكيد حضور المتسابق في المركز'],
                                ['num' => '4', 'icon' => 'quran', 'title' => 'التحكيم المباشر', 'desc' => 'سحب الباقة ورصد درجات التلاوة'],
                                ['num' => '5', 'icon' => 'results', 'title' => 'اعتماد النتائج', 'desc' => 'حساب النسبة وتصدير التقارير'],
                            ];
                        @endphp
                        @foreach ($steps as $step)
                            <div class="bg-white rounded-xl border border-brand-sand-200 p-2.5 text-center relative shadow-xs">
                                <div class="w-5 h-5 rounded-full bg-brand-gold-400 text-brand-teal-950 font-extrabold text-[11px] flex items-center justify-center mx-auto mb-1.5">
                                    {{ $step['num'] }}
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-brand-teal-50 border border-brand-teal-200 text-brand-teal-700 flex items-center justify-center mx-auto mb-1.5">
                                    <x-svgicon.brand-icon :name="$step['icon']" class="w-4 h-4" />
                                </div>
                                <div class="font-bold text-[11px] text-brand-teal-950 mb-0.5">{{ $step['title'] }}</div>
                                <div class="text-[9.5px] text-gray-600 leading-snug">{{ $step['desc'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 4 User Roles Grid --}}
                <div>
                    <h3 class="text-sm font-extrabold text-brand-teal-900 mb-2.5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-gold-400"></span>
                        <span>الأدوار والصلاحيات داخل البرنامج</span>
                    </h3>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Admin --}}
                        <div class="bg-white rounded-2xl border border-brand-sand-200 p-4 shadow-xs border-r-4 border-r-brand-teal-700">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-brand-teal-700 text-white flex items-center justify-center">
                                    <x-svgicon.brand-icon name="users" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-extrabold text-sm text-brand-teal-950">مدير النظام (Admin)</div>
                                    <div class="text-[10px] text-brand-gold-700 font-bold">صلاحيات إدارية كاملة</div>
                                </div>
                            </div>
                            <ul class="text-[11px] text-gray-700 space-y-1 list-disc list-inside leading-relaxed">
                                <li>تفعيل التصفيات والمراحل وإدارة المراكز واللجان.</li>
                                <li>تعيين المستخدمين وتحديد <strong>رئيس كل لجنة</strong>.</li>
                                <li>إدارة بيانات المتسابقين ومعايير التقييم.</li>
                                <li>استعراض النتائج النهائية والإحصائيات وتصدير ملفات إكسل.</li>
                            </ul>
                        </div>

                        {{-- Question Admin --}}
                        <div class="bg-white rounded-2xl border border-brand-sand-200 p-4 shadow-xs border-r-4 border-r-brand-gold-500">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-brand-gold-500 text-white flex items-center justify-center">
                                    <x-svgicon.brand-icon name="questions" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-extrabold text-sm text-brand-teal-950">مشرف الأسئلة (Question Admin)</div>
                                    <div class="text-[10px] text-brand-gold-700 font-bold">إعداد ومراجعة الباقات القرآنية</div>
                                </div>
                            </div>
                            <ul class="text-[11px] text-gray-700 space-y-1 list-disc list-inside leading-relaxed">
                                <li>إنشاء وتعديل باقات الأسئلة للمستوى الأول والثاني.</li>
                                <li>تحديد السور ومواضع الآيات بدقة لكل سؤال.</li>
                                <li>تكرار الأسئلة وتنظيم ترتيبها داخل الباقة.</li>
                            </ul>
                        </div>

                        {{-- Organizer --}}
                        <div class="bg-white rounded-2xl border border-brand-sand-200 p-4 shadow-xs border-r-4 border-r-brand-teal-500">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-brand-teal-500 text-white flex items-center justify-center">
                                    <x-svgicon.brand-icon name="attendance" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-extrabold text-sm text-brand-teal-950">المنظّم / الاستقبال (Organizer)</div>
                                    <div class="text-[10px] text-brand-teal-700 font-bold">إدارة حضور المتسابقين بالمراكز</div>
                                </div>
                            </div>
                            <ul class="text-[11px] text-gray-700 space-y-1 list-disc list-inside leading-relaxed">
                                <li>البحث السريع عن المتسابقين المسجلين في المركز.</li>
                                <li>تأكيد حضور المتسابق وإدخاله إلى قائمة انتظار اللجنة.</li>
                                <li>تحديث القائمة فورياً لمتابعة المتسابقين المتبقين.</li>
                            </ul>
                        </div>

                        {{-- Judge & Leader --}}
                        <div class="bg-white rounded-2xl border border-brand-sand-200 p-4 shadow-xs border-r-4 border-r-brand-teal-900">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-brand-teal-900 text-brand-gold-300 flex items-center justify-center">
                                    <x-svgicon.brand-icon name="quran" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-extrabold text-sm text-brand-teal-950">المحكّم ورئيس اللجنة (Judge)</div>
                                    <div class="text-[10px] text-brand-gold-700 font-bold">التقييم المباشر واعتماد الدرجات</div>
                                </div>
                            </div>
                            <ul class="text-[11px] text-gray-700 space-y-1 list-disc list-inside leading-relaxed">
                                <li><strong>رئيس اللجنة:</strong> يختار باقة الأسئلة للمتسابق ويحدد السؤال الملغي.</li>
                                <li><strong>أعضاء اللجنة:</strong> استعراض الآيات بخط المصحف ورصد الدرجات.</li>
                                <li>مراجعة النتيجة التفصيلية واعتماد إنهاء المسابقة للمتسابق.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page Footer --}}
            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 2 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 3: LOGIN & MAIN NAVIGATION DASHBOARD
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-5">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم الثاني</div>
                            <h2 class="font-display text-xl font-bold">تسجيل الدخول وواجهة لوحة التحكم</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Section A: Login Mockup --}}
                <div class="grid grid-cols-12 gap-4 items-center mb-6">
                    <div class="col-span-7">
                        <div class="mockup-window">
                            <div class="mockup-topbar">
                                <span class="text-[10px] font-bold text-brand-gold-200">شاشة تسجيل الدخول الموحدة (/login)</span>
                                <div class="flex gap-1">
                                    <span class="w-2 h-2 rounded-full bg-brand-gold-400"></span>
                                    <span class="w-2 h-2 rounded-full bg-brand-teal-400"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 bg-white">
                                <div class="col-span-5 bg-brand-teal-900 p-3.5 text-white flex flex-col justify-center items-center text-center relative overflow-hidden">
                                    <div class="w-8 h-8 rounded-full bg-brand-gold-400/20 border border-brand-gold-400/50 flex items-center justify-center mb-2">
                                        <x-svgicon.rosette class="w-5 h-5" />
                                    </div>
                                    <div class="font-display text-xs font-bold leading-relaxed">مسابقة مؤسسة الشيخ مستهيل</div>
                                    <div class="text-[9px] text-brand-gold-200 mt-1">للقرآن الكريم وقراءاته</div>
                                </div>
                                <div class="col-span-7 p-4 space-y-2.5">
                                    <div class="text-center mb-2">
                                        <img src="{{ asset('logo.png') }}" alt="" class="h-7 mx-auto mb-1">
                                        <div class="text-xs font-extrabold text-brand-teal-950">تسجيل الدخول إلى النظام</div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[10px] font-bold text-brand-teal-900 mb-1">
                                            <span>الرقم المدني</span>
                                            <span class="callout-badge">1</span>
                                        </div>
                                        <div class="bg-brand-sand-50 border border-brand-sand-200 rounded-lg px-2.5 py-1.5 text-[10px] text-gray-500">
                                            12345678
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[10px] font-bold text-brand-teal-900 mb-1">
                                            <span>كلمة المرور</span>
                                            <span class="callout-badge">2</span>
                                        </div>
                                        <div class="bg-brand-sand-50 border border-brand-sand-200 rounded-lg px-2.5 py-1.5 text-[10px] text-gray-500 flex justify-between">
                                            <span>••••••••</span>
                                            <span class="text-brand-gold-700 font-bold">عرض</span>
                                        </div>
                                    </div>
                                    <div class="pt-1 flex items-center gap-2">
                                        <div class="flex-1 bg-brand-teal-800 text-white text-center py-1.5 rounded-lg text-[11px] font-bold">
                                            دخول إلى النظام
                                        </div>
                                        <span class="callout-badge">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-5 space-y-2.5">
                        <h3 class="text-sm font-extrabold text-brand-teal-900">خطوات الدخول للبرنامج:</h3>
                        <div class="flex items-start gap-2 text-xs text-gray-700">
                            <span class="callout-badge mt-0.5">1</span>
                            <p>أدخل <strong>الرقم المدني</strong> الخاص بالمستخدم كما هو مسجل لدى إدارة المسابقة.</p>
                        </div>
                        <div class="flex items-start gap-2 text-xs text-gray-700">
                            <span class="callout-badge mt-0.5">2</span>
                            <p>أدخل <strong>كلمة المرور</strong>، ويمكنك الضغط على «عرض» للتأكد من صحة الأحرف.</p>
                        </div>
                        <div class="flex items-start gap-2 text-xs text-gray-700">
                            <span class="callout-badge mt-0.5">3</span>
                            <p>اضغط على <strong>دخول إلى النظام</strong>، وسيتم توجيهك تلقائياً للوحة المخصصة لصلاحيتك (مدير، منظم، محكم).</p>
                        </div>
                    </div>
                </div>

                {{-- Section B: Dashboard & Sidebar Mockup --}}
                <div>
                    <h3 class="text-sm font-extrabold text-brand-teal-900 mb-2.5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-gold-400"></span>
                        <span>مكونات القائمة الجانبية ولوحة التحكم الرئيسية</span>
                    </h3>

                    <div class="mockup-window">
                        <div class="mockup-topbar">
                            <span class="text-[10px] font-bold text-brand-gold-200">واجهة لوحة التحكم (/dashboard)</span>
                            <span class="text-[10px] text-brand-teal-200">المرحلة النشطة: التصفيات الأولية</span>
                        </div>
                        <div class="grid grid-cols-12 min-h-[195px]">
                            {{-- Simulated Sidebar --}}
                            <div class="col-span-4 bg-brand-teal-900 text-white p-3 space-y-2 border-l border-brand-gold-400/30">
                                <div class="bg-white rounded-lg p-1.5 text-center">
                                    <img src="{{ asset('logo.png') }}" alt="" class="h-6 mx-auto">
                                </div>
                                <div class="bg-white/10 rounded-lg p-2 border border-brand-gold-400/30 flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] font-bold">د. أحمد المعشني</div>
                                        <div class="text-[9px] text-brand-gold-300">مدير النظام • التصفيات الأولية</div>
                                    </div>
                                    <span class="callout-badge">4</span>
                                </div>
                                <div class="space-y-1 text-[10px]">
                                    <div class="bg-brand-gold-400 text-brand-teal-950 font-bold rounded-lg px-2.5 py-1.5 flex items-center justify-between">
                                        <span>الرئيسية</span>
                                        <span class="callout-badge bg-white">5</span>
                                    </div>
                                    <div class="px-2.5 py-1 text-brand-teal-100">• التصفيات والمراكز</div>
                                    <div class="px-2.5 py-1 text-brand-teal-100">• اللجان والمتسابقون</div>
                                    <div class="px-2.5 py-1 text-brand-teal-100">• باقات الأسئلة والتقييم</div>
                                    <div class="px-2.5 py-1 text-brand-gold-200 font-bold">• دليل البرنامج (PDF)</div>
                                </div>
                            </div>

                            {{-- Simulated Main Area --}}
                            <div class="col-span-8 bg-brand-sand-50 p-3.5 space-y-3">
                                <div class="bg-brand-teal-800 text-white rounded-xl p-3 border-b-2 border-brand-gold-400 flex items-center justify-between">
                                    <div>
                                        <div class="text-[9px] text-brand-gold-200 font-bold">المرحلة النشطة: التصفيات الأولية</div>
                                        <div class="text-xs font-bold mt-0.5">مرحباً بك في لوحة التحكم</div>
                                    </div>
                                    <span class="callout-badge">6</span>
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div class="bg-white rounded-xl p-2.5 border border-brand-sand-200 text-center">
                                        <div class="text-[10px] font-bold text-brand-teal-900">التصفيات</div>
                                        <div class="text-[9px] text-gray-500 mt-0.5">إدارة المراحل</div>
                                    </div>
                                    <div class="bg-white rounded-xl p-2.5 border border-brand-sand-200 text-center">
                                        <div class="text-[10px] font-bold text-brand-teal-900">اللجان</div>
                                        <div class="text-[9px] text-gray-500 mt-0.5">توزيع المحكمين</div>
                                    </div>
                                    <div class="bg-white rounded-xl p-2.5 border border-brand-sand-200 text-center">
                                        <div class="text-[10px] font-bold text-brand-teal-900">النتائج</div>
                                        <div class="text-[9px] text-gray-500 mt-0.5">تصدير وطباعة</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Legend for 4, 5, 6 --}}
                    <div class="grid grid-cols-3 gap-3 mt-3">
                        <div class="bg-white rounded-xl border border-brand-sand-200 p-2.5 flex items-start gap-2 text-[11px]">
                            <span class="callout-badge">4</span>
                            <span><strong>بطاقة المستخدم:</strong> تعرض اسمك وصلاحيتك والمرحلة النشطة حالياً.</span>
                        </div>
                        <div class="bg-white rounded-xl border border-brand-sand-200 p-2.5 flex items-start gap-2 text-[11px]">
                            <span class="callout-badge">5</span>
                            <span><strong>القائمة الجانبية:</strong> تتكيف تلقائياً لتعرض الأقسام الخاصة بدورك فقط.</span>
                        </div>
                        <div class="bg-white rounded-xl border border-brand-sand-200 p-2.5 flex items-start gap-2 text-[11px]">
                            <span class="callout-badge">6</span>
                            <span><strong>البطاقات السريعة:</strong> وصول مباشر لأهم المهام اليومية بنقرة واحدة.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 3 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 4: STAGES, CENTERS & COMMITTEES MANAGEMENT
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-5">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم الثالث</div>
                            <h2 class="font-display text-xl font-bold">إدارة التصفيات، المراكز، ولجان التحكيم</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Stages & Centers Mockup --}}
                <div class="grid grid-cols-12 gap-4 mb-5">
                    <div class="col-span-6">
                        <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                            <span class="callout-badge">1</span>
                            <span>تفعيل مرحلة التصفية (التصفيات)</span>
                        </h3>
                        <div class="mockup-window">
                            <div class="mockup-topbar">
                                <span class="text-[10px] text-brand-gold-200 font-bold">إدارة التصفيات (/stage/index)</span>
                            </div>
                            <div class="p-3 bg-white">
                                <table class="w-full text-[10px] text-right">
                                    <thead>
                                        <tr class="bg-brand-teal-50 text-brand-teal-900">
                                            <th class="p-1.5">المرحلة</th>
                                            <th class="p-1.5 text-center">الحالة</th>
                                            <th class="p-1.5 text-center">تحكم</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-brand-sand-100">
                                        <tr class="bg-brand-teal-50/60">
                                            <td class="p-1.5 font-bold">التصفيات الأولية</td>
                                            <td class="p-1.5 text-center"><span class="px-2 py-0.5 rounded-full bg-brand-teal-100 text-brand-teal-800 font-bold">نشط</span></td>
                                            <td class="p-1.5 text-center text-brand-gold-700 font-bold">معتمد</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1.5 font-bold">التصفيات النهائية</td>
                                            <td class="p-1.5 text-center"><span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">غير نشط</span></td>
                                            <td class="p-1.5 text-center"><span class="px-2 py-0.5 rounded bg-brand-teal-800 text-white">تفعيل</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-700 mt-2 leading-relaxed">
                            عند تفعيل مرحلة معينة، تعمل جميع المراكز واللجان وقوائم المتسابقين تلقائياً على هذه المرحلة النشطة.
                        </p>
                    </div>

                    <div class="col-span-6">
                        <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                            <span class="callout-badge">2</span>
                            <span>إدارة المراكز والتقارير الإحصائية</span>
                        </h3>
                        <div class="mockup-window">
                            <div class="mockup-topbar">
                                <span class="text-[10px] text-brand-gold-200 font-bold">المراكز المعتمدة (/center/index)</span>
                            </div>
                            <div class="p-3 bg-white space-y-2">
                                <div class="flex items-center justify-between p-2 rounded-lg bg-brand-sand-50 border border-brand-sand-200">
                                    <div>
                                        <div class="text-[11px] font-bold text-brand-teal-950">مركز مسقط</div>
                                        <div class="text-[9px] text-brand-gold-700 font-bold">عدد اللجان: 4 لجان</div>
                                    </div>
                                    <div class="flex gap-1.5 text-[9px]">
                                        <span class="px-2 py-1 rounded bg-brand-teal-800 text-white font-bold">المتسابقون</span>
                                        <span class="px-2 py-1 rounded bg-white border border-brand-teal-200 text-brand-teal-800 font-bold">التقرير الإحصائي</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-2 rounded-lg bg-brand-sand-50 border border-brand-sand-200">
                                    <div>
                                        <div class="text-[11px] font-bold text-brand-teal-950">مركز صلالة</div>
                                        <div class="text-[9px] text-brand-gold-700 font-bold">عدد اللجان: 3 لجان</div>
                                    </div>
                                    <div class="flex gap-1.5 text-[9px]">
                                        <span class="px-2 py-1 rounded bg-brand-teal-800 text-white font-bold">المتسابقون</span>
                                        <span class="px-2 py-1 rounded bg-white border border-brand-teal-200 text-brand-teal-800 font-bold">التقرير الإحصائي</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-700 mt-2 leading-relaxed">
                            يتيح قسم المراكز استعراض قوائم المتسابقين بكل مركز وطباعة التقرير الإحصائي الفوري لنسب الإنجاز.
                        </p>
                    </div>
                </div>

                {{-- Committees Management Mockup --}}
                <div>
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">3</span>
                        <span>تنظيم اللجان وتعيين رئيس اللجنة (اللجان)</span>
                    </h3>

                    <div class="mockup-window mb-3">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">إدارة اللجان (/committee/index)</span>
                            <span class="text-[9px] bg-brand-gold-400/20 text-brand-gold-200 px-2 py-0.5 rounded">تعيين رئيس اللجنة</span>
                        </div>
                        <div class="p-3.5 bg-brand-sand-50 space-y-2.5">
                            <div class="bg-white rounded-xl border border-brand-sand-200 p-3">
                                <div class="flex items-center justify-between border-b border-brand-sand-100 pb-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-brand-teal-950">اللجنة الأولى — ذكور</span>
                                        <span class="px-2 py-0.5 rounded-full bg-brand-teal-50 text-brand-teal-800 text-[9px] font-bold">نشطة</span>
                                    </div>
                                    <span class="text-[10px] text-brand-teal-700 font-bold">مركز مسقط • 24 متسابق</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-[10px]">
                                    <div class="p-2 rounded-lg bg-brand-gold-50 border border-brand-gold-300 flex items-center justify-between">
                                        <span class="font-bold text-brand-teal-950">الشيخ سالم الحارثي</span>
                                        <span class="px-1.5 py-0.5 rounded bg-brand-gold-400 text-brand-teal-950 font-extrabold text-[9px]">رئيس اللجنة</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-brand-sand-50 border border-brand-sand-200 flex items-center justify-between">
                                        <span class="font-semibold text-gray-800">أ. محمد البلوشي</span>
                                        <span class="text-[9px] text-brand-teal-700">محكّم</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-brand-sand-50 border border-brand-sand-200 flex items-center justify-between">
                                        <span class="font-semibold text-gray-800">أ. خالد المعشني</span>
                                        <span class="text-[9px] text-brand-teal-700">محكّم</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Important Rule Callout --}}
                    <div class="bg-brand-gold-50 border border-brand-gold-300 border-r-4 border-r-brand-gold-500 rounded-xl p-3.5 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-gold-400 text-brand-teal-950 flex items-center justify-center shrink-0 font-extrabold text-sm">!</div>
                        <div class="text-xs text-brand-teal-950 leading-relaxed">
                            <strong>قاعدة تنظيمية مهمة:</strong> يجب تعيين <strong>رئيس واحد لكل لجنة</strong> من صفحة اللجان؛ إذ إن رئيس اللجنة هو المخوّل حصرياً باختيار باقة الأسئلة للمتسابق الحاضر، وتحديد السؤال الملغي إن وُجد، لتظهر الأسئلة فوراً لبقية أعضاء اللجنة.
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 4 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 5: QUESTION SETS & EVALUATION ELEMENTS
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-5">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم الرابع</div>
                            <h2 class="font-display text-xl font-bold">إدارة باقات الأسئلة وعناصر التقييم</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Question Sets Mockup --}}
                <div class="mb-5">
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">1</span>
                        <span>بناء باقات الأسئلة القرآنية (المستوى الأول والثاني)</span>
                    </h3>

                    <div class="mockup-window mb-3">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">باقات الأسئلة — المستوى الأول (/questionset/index/1)</span>
                            <div class="flex gap-1.5 text-[9px]">
                                <span class="px-2 py-0.5 rounded bg-brand-gold-400 text-brand-teal-950 font-bold">+ إنشاء باقة جديدة</span>
                                <span class="px-2 py-0.5 rounded bg-white/15 text-white">طباعة الباقات</span>
                            </div>
                        </div>
                        <div class="p-3.5 bg-brand-sand-50 grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-xl border border-brand-sand-200 p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-extrabold text-xs text-brand-teal-950">الباقة رقم (1)</span>
                                    <span class="px-2 py-0.5 rounded-full bg-brand-teal-50 text-brand-teal-800 text-[9px] font-bold">6 أسئلة</span>
                                </div>
                                <div class="space-y-1.5 text-[10px]">
                                    <div class="flex justify-between bg-brand-sand-50 px-2 py-1 rounded">
                                        <span class="font-bold">السؤال 1: سورة البقرة</span>
                                        <span class="text-brand-teal-700">آية 142 – 148</span>
                                    </div>
                                    <div class="flex justify-between bg-brand-sand-50 px-2 py-1 rounded">
                                        <span class="font-bold">السؤال 2: سورة آل عمران</span>
                                        <span class="text-brand-teal-700">آية 92 – 98</span>
                                    </div>
                                    <div class="flex justify-between bg-brand-sand-50 px-2 py-1 rounded">
                                        <span class="font-bold">السؤال 3: سورة يوسف</span>
                                        <span class="text-brand-teal-700">آية 50 – 57</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl border border-brand-sand-200 p-3 flex flex-col justify-between">
                                <div>
                                    <div class="font-extrabold text-xs text-brand-teal-950 mb-1.5">إضافة سؤال جديد للباقة</div>
                                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                                        <div class="bg-brand-sand-50 border border-brand-sand-200 rounded p-1.5">
                                            <div class="text-[9px] text-gray-500">السورة</div>
                                            <div class="font-bold text-brand-teal-900">سورة الكهف</div>
                                        </div>
                                        <div class="bg-brand-sand-50 border border-brand-sand-200 rounded p-1.5">
                                            <div class="text-[9px] text-gray-500">نطاق الآيات</div>
                                            <div class="font-bold text-brand-teal-900">من 1 إلى 10</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2.5 text-[10px] text-brand-teal-800 bg-brand-teal-50 p-2 rounded-lg border border-brand-teal-200">
                                    يجلب النظام النص القرآني بالرسم العثماني تلقائياً بمجرد اختيار السورة وأرقام الآيات.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Evaluation Elements Mockup --}}
                <div>
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">2</span>
                        <span>ضبط معايير وعناصر التقييم والدرجة القصوى</span>
                    </h3>

                    <div class="mockup-window mb-3">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">عناصر التقييم (/evaluation_element/index/1)</span>
                            <span class="text-[9px] bg-brand-gold-400 text-brand-teal-950 px-2 py-0.5 rounded font-bold">+ إضافة عنصر جديد</span>
                        </div>
                        <div class="p-3 bg-white">
                            <table class="w-full text-[11px] text-right">
                                <thead>
                                    <tr class="bg-brand-teal-50 text-brand-teal-900">
                                        <th class="p-2">عنصر التقييم</th>
                                        <th class="p-2 text-center">الحد الأعلى للدرجة</th>
                                        <th class="p-2 text-center">المستوى</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-sand-100">
                                    <tr>
                                        <td class="p-2 font-bold">جودة الحفظ والإتقان</td>
                                        <td class="p-2 text-center"><span class="px-2.5 py-0.5 rounded-full bg-brand-gold-100 text-brand-gold-900 font-bold">15 درجة</span></td>
                                        <td class="p-2 text-center text-gray-600">المستوى الأول</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 font-bold">أحكام التجويد والأداء</td>
                                        <td class="p-2 text-center"><span class="px-2.5 py-0.5 rounded-full bg-brand-gold-100 text-brand-gold-900 font-bold">5 درجات</span></td>
                                        <td class="p-2 text-center text-gray-600">المستوى الأول</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 font-bold">الوقف والابتداء وحسن الصوت</td>
                                        <td class="p-2 text-center"><span class="px-2.5 py-0.5 rounded-full bg-brand-gold-100 text-brand-gold-900 font-bold">4 درجات</span></td>
                                        <td class="p-2 text-center text-gray-600">المستوى الأول</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white rounded-xl border border-brand-sand-200 p-3">
                            <div class="font-extrabold text-brand-teal-900 mb-1">المستوى الأول (الدرجة القصوى: 120)</div>
                            <p class="text-[11px] text-gray-600">يحسب النظام النسبة المئوية تلقائياً بقسمة المجموع النهائي على 120 درجة.</p>
                        </div>
                        <div class="bg-white rounded-xl border border-brand-sand-200 p-3">
                            <div class="font-extrabold text-brand-teal-900 mb-1">المستوى الثاني (الدرجة القصوى: 100)</div>
                            <p class="text-[11px] text-gray-600">يحسب النظام النسبة المئوية تلقائياً بقسمة المجموع النهائي على 100 درجة.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 5 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 6: ATTENDANCE & PACKAGE SELECTION
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-5">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم الخامس</div>
                            <h2 class="font-display text-xl font-bold">تسجيل حضور المتسابقين واختيار باقة الأسئلة</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Attendance Mockup --}}
                <div class="mb-5">
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">1</span>
                        <span>تسجيل الحضور من قِبل المنظّم (/student/attendance_index)</span>
                    </h3>

                    <div class="mockup-window mb-2.5">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">بوابة المنظم — تسجيل حضور المتسابقين</span>
                            <span class="text-[9px] bg-white/15 text-white px-2 py-0.5 rounded">تحديث القائمة</span>
                        </div>
                        <div class="p-3 bg-white space-y-2">
                            <div class="flex items-center justify-between bg-brand-sand-50 border border-brand-sand-200 rounded-xl px-3 py-2">
                                <div>
                                    <div class="text-xs font-bold text-brand-teal-950">عبدالله بن سعيد المعشني</div>
                                    <div class="text-[10px] text-gray-500">المستوى الأول • اللجنة الأولى</div>
                                </div>
                                <span class="px-3 py-1 rounded-lg bg-brand-teal-700 text-white text-[10px] font-bold">تسجيل حضور ✓</span>
                            </div>
                            <div class="flex items-center justify-between bg-brand-teal-50/60 border border-brand-teal-200 rounded-xl px-3 py-2">
                                <div>
                                    <div class="text-xs font-bold text-brand-teal-950">يوسف بن علي الرواحي</div>
                                    <div class="text-[10px] text-gray-500">المستوى الثاني • اللجنة الأولى</div>
                                </div>
                                <span class="px-3 py-1 rounded-lg bg-brand-gold-100 text-brand-gold-900 text-[10px] font-bold">تم التحضير (حاضر)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-700 leading-relaxed">
                        بمجرد قيام المنظّم بالضغط على <strong>تسجيل حضور</strong>، ينتقل اسم المتسابق تلقائياً إلى شاشة <strong>المتسابقون الحاضرون</strong> لدى رئيس اللجنة المعنية.
                    </p>
                </div>

                {{-- Present Students & Package Selection Mockup --}}
                <div>
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">2</span>
                        <span>شاشة المتسابقين الحاضرين واختيار الباقة (/student/present_index)</span>
                    </h3>

                    <div class="mockup-window mb-3">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">معلومات اللجنة وقائمة الحاضرين</span>
                            <span class="text-[9px] text-brand-teal-200">المركز: مسقط • اللجنة الأولى</span>
                        </div>
                        <div class="p-3.5 bg-brand-sand-50 space-y-2.5">
                            <div class="grid grid-cols-3 gap-2 text-center text-[10px]">
                                <div class="bg-white p-2 rounded-lg border border-brand-sand-200">
                                    <span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-800 font-bold border border-amber-200">حاضر</span>
                                    <div class="text-[9px] text-gray-500 mt-1">بانتظار اختيار الباقة</div>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-brand-sand-200">
                                    <span class="inline-block px-2 py-0.5 rounded bg-rose-50 text-rose-800 font-bold border border-rose-200">مع اللجنة</span>
                                    <div class="text-[9px] text-gray-500 mt-1">قيد التقييم حالياً</div>
                                </div>
                                <div class="bg-white p-2 rounded-lg border border-brand-sand-200">
                                    <span class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">أنهى المسابقة</span>
                                    <div class="text-[9px] text-gray-500 mt-1">تم اعتماد نتيجته</div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl border border-brand-sand-200 p-3 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-bold text-brand-teal-950">عبدالله بن سعيد المعشني</div>
                                    <div class="text-[10px] text-brand-gold-700 font-semibold">الحالة: حاضر — جاهز لسحب الباقة</div>
                                </div>
                                <span class="px-3 py-1.5 rounded-lg bg-brand-gold-400 text-brand-teal-950 text-[10px] font-extrabold">اختيار باقة الأسئلة</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-brand-sand-200 p-3.5 text-xs space-y-1.5 text-gray-700">
                        <div class="font-extrabold text-brand-teal-900">آلية التنسيق بين رئيس اللجنة والمحكمين:</div>
                        <p>• يقوم <strong>رئيس اللجنة</strong> باختيار باقة الأسئلة المناسبة للمتسابق وتتحول حالته إلى <strong>«مع اللجنة»</strong>.</p>
                        <p>• يقوم بقية <strong>أعضاء اللجنة (المحكمون)</strong> بالضغط على زر <strong>«تحديث القائمة»</strong> ليظهر لهم المتسابق مباشرة ويبدؤوا التقييم معاً.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 6 من 7</span>
            </div>
        </div>
    </section>

    {{-- =======================================================================
         PAGE 7: ELECTRONIC EVALUATION INTERFACE & FINAL RESULTS
         ======================================================================= --}}
    <section class="pdf-page bg-islamic-pattern">
        <div class="pdf-page-content">
            <div>
                <div class="flex items-center justify-between bg-brand-teal-900 text-white rounded-2xl px-5 py-3.5 border-b-4 border-brand-gold-400 mb-4">
                    <div class="flex items-center gap-3">
                        <x-svgicon.rosette class="w-7 h-7" />
                        <div>
                            <div class="text-[11px] text-brand-gold-300 font-bold">القسم السادس</div>
                            <h2 class="font-display text-xl font-bold">شاشة التحكيم الإلكتروني والنتائج النهائية</h2>
                        </div>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-9 bg-white rounded-lg px-2 py-1">
                </div>

                {{-- Split-Screen Evaluation Interface Mockup --}}
                <div class="mb-4">
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">1</span>
                        <span>واجهة التحكيم المقسّمة: المصحف + رصد الدرجات (/memorization/start)</span>
                    </h3>

                    <div class="mockup-window">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">شاشة تقييم المتسابق — السؤال الأول</span>
                            <span class="text-[9px] bg-white/15 text-white px-2 py-0.5 rounded">تبديل الوضع الليلي/النهاري</span>
                        </div>
                        <div class="grid grid-cols-12">
                            {{-- Right side in RTL: Quran display --}}
                            <div class="col-span-5 bg-brand-teal-900 text-white p-3.5 text-center border-l border-brand-gold-400/30">
                                <div class="inline-block px-3 py-0.5 rounded-lg bg-brand-gold-400/25 border border-brand-gold-400/50 text-brand-gold-200 text-[10px] font-bold mb-1.5">
                                    السؤال: 1
                                </div>
                                <div class="font-bold text-xs mb-2">سورة البقرة</div>
                                <div class="font-display text-sm leading-loose bg-white/10 rounded-xl p-2.5 text-brand-sand-50">
                                    ﴿ سَيَقُولُ السُّفَهَاءُ مِنَ النَّاسِ مَا وَلَّاهُمْ عَن قِبْلَتِهِمُ الَّتِي كَانُوا عَلَيْهَا ﴾
                                </div>
                            </div>

                            {{-- Left side in RTL: Score controls --}}
                            <div class="col-span-7 bg-white p-3.5 space-y-2.5">
                                <div class="flex items-center justify-between bg-brand-sand-50 rounded-xl p-2 border border-brand-sand-200">
                                    <div>
                                        <div class="text-[11px] font-bold text-brand-teal-950">جودة الحفظ والإتقان</div>
                                        <div class="text-[9px] text-gray-500">الحد الأعلى: 15 درجة</div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-7 h-7 rounded-lg bg-brand-teal-700 text-white font-bold flex items-center justify-center text-sm">+</span>
                                        <span class="w-10 text-center font-extrabold text-sm text-brand-teal-950">14.5</span>
                                        <span class="w-7 h-7 rounded-lg bg-rose-700 text-white font-bold flex items-center justify-center text-sm">-</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-brand-sand-50 rounded-xl p-2 border border-brand-sand-200">
                                    <div>
                                        <div class="text-[11px] font-bold text-brand-teal-950">أحكام التجويد والأداء</div>
                                        <div class="text-[9px] text-gray-500">الحد الأعلى: 5 درجات</div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-7 h-7 rounded-lg bg-brand-teal-700 text-white font-bold flex items-center justify-center text-sm">+</span>
                                        <span class="w-10 text-center font-extrabold text-sm text-brand-teal-950">4.5</span>
                                        <span class="w-7 h-7 rounded-lg bg-rose-700 text-white font-bold flex items-center justify-center text-sm">-</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-xs font-extrabold text-brand-teal-900">المجموع: 19.0 / 20</span>
                                    <span class="px-4 py-1.5 rounded-lg bg-brand-gold-400 text-brand-teal-950 text-[11px] font-extrabold">حفظ التقييم ✓</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Final Results & Excel Export Mockup --}}
                <div class="mb-4">
                    <h3 class="text-xs font-extrabold text-brand-teal-900 mb-2 flex items-center gap-1.5">
                        <span class="callout-badge">2</span>
                        <span>النتائج النهائية المعتمدة والتصدير إلى Excel (/results/list)</span>
                    </h3>

                    <div class="mockup-window">
                        <div class="mockup-topbar">
                            <span class="text-[10px] text-brand-gold-200 font-bold">قائمة المتسابقين المعتمدين ونتائجهم</span>
                            <div class="flex gap-1.5 text-[9px]">
                                <span class="px-2 py-0.5 rounded bg-brand-teal-600 text-white font-bold">طباعة التقرير</span>
                                <span class="px-2 py-0.5 rounded bg-brand-gold-400 text-brand-teal-950 font-bold">تصدير إكسل</span>
                            </div>
                        </div>
                        <div class="p-3 bg-white">
                            <div class="flex gap-2 mb-2 text-[9px] text-gray-600">
                                <span class="px-2 py-1 rounded bg-brand-sand-50 border border-brand-sand-200">تصفية: كل المراكز ▾</span>
                                <span class="px-2 py-1 rounded bg-brand-sand-50 border border-brand-sand-200">كل الأجناس ▾</span>
                                <span class="px-2 py-1 rounded bg-brand-sand-50 border border-brand-sand-200">كل المستويات ▾</span>
                            </div>
                            <table class="w-full text-[10px] text-right">
                                <thead>
                                    <tr class="bg-brand-teal-50 text-brand-teal-900">
                                        <th class="p-1.5">#</th>
                                        <th class="p-1.5">اسم المتسابق</th>
                                        <th class="p-1.5">المستوى</th>
                                        <th class="p-1.5">المركز</th>
                                        <th class="p-1.5 text-center">المجموع</th>
                                        <th class="p-1.5 text-center">النسبة %</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-sand-100">
                                    <tr>
                                        <td class="p-1.5 font-bold">1</td>
                                        <td class="p-1.5 font-bold text-brand-teal-950">عبدالله بن سعيد المعشني</td>
                                        <td class="p-1.5">المستوى الأول</td>
                                        <td class="p-1.5">مركز مسقط</td>
                                        <td class="p-1.5 text-center font-bold">116.5</td>
                                        <td class="p-1.5 text-center font-extrabold text-brand-teal-800">97.08%</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1.5 font-bold">2</td>
                                        <td class="p-1.5 font-bold text-brand-teal-950">يوسف بن علي الرواحي</td>
                                        <td class="p-1.5">المستوى الثاني</td>
                                        <td class="p-1.5">مركز صلالة</td>
                                        <td class="p-1.5 text-center font-bold">95.0</td>
                                        <td class="p-1.5 text-center font-extrabold text-brand-teal-800">95.00%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Closing Institutional Banner --}}
                <div class="bg-brand-teal-950 text-white rounded-2xl p-4 border-t-4 border-brand-gold-400 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-display text-base font-bold text-brand-gold-200 mb-0.5">
                            نسأل الله التوفيق والسداد لجميع القائمين والمتسابقين
                        </div>
                        <p class="text-[11px] text-brand-teal-100/85">
                            مسابقة مؤسسة الشيخ مستهيل بن أحمد بن علي المعشني الوقفية الخاصة للقرآن الكريم وقراءاته
                        </p>
                    </div>
                    <img src="{{ asset('logo.png') }}" alt="الشعار" class="h-11 bg-white rounded-xl px-2.5 py-1 shrink-0">
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-brand-teal-800 border-t border-brand-sand-200 pt-2.5">
                <span>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته — الدليل الإرشادي</span>
                <span class="font-extrabold text-brand-teal-950">صفحة 7 من 7</span>
            </div>
        </div>
    </section>
    </div>

</body>

</html>
