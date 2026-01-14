<!DOCTYPE html>
<html lang="en-GB" dir="rtl">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <title>{{ $title ?? 'مسابقة فاستمسك' }}</title>

    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <link rel="icon" type="image/svg" href="{{ asset('assets/images/favicon.svg') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="flex gap-1 min-h-screen h-full">

        <nav class="print:hidden w-72 bg-white shadow-lg min-h-screen">
            <div class="h-full p-4 flex flex-col">
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition-all border border-red-100 active:scale-95">

                        <svg class="w-4 h-4 rotate-90 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>

                        <span>تسجيل الخروج</span>
                    </button>
                </form>
                <a href="/"
                    class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white text-purple-600">
                    مسابقة فاستمسك
                </a>
                <div>
                    <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100 shadow-sm">
                        <div class="text-gray-900 font-bold text-base mb-0.5">
                            {{ Auth::user()->name }}
                        </div>

                        <div
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 mb-2">
                            {{ __(Auth::user()->user_type) }}
                        </div>
                        @if (Auth::user()->user_type == 'judge')
                            @php
                                $currentStage = App\Models\Stage::latest('id')->first();
                            @endphp
                            <div
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 mb-2">
                                @if (Auth::user()->isCommitteeLeader($currentStage->id))
                                    رئيس اللجنة
                                @endif
                            </div>

                        @endif
                        <div class="text-gray-500 text-xs flex items-center gap-1">
                            <span class="font-medium text-gray-400 italic">رقم المدني:</span>
                            {{ Auth::user()->national_id }}
                        </div>
                    </div>


                </div>
                <div class="py-4 px-2 border-b mb-4">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-100 transition-all active:scale-95 font-bold mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        الرئيسية
                    </a>
                </div>

                <!-- Navigation Menu -->
                <ul class="space-y-1 flex-1 overflow-y-auto pb-4">

                    @if (auth()->user()->user_type == 'admin')
                        <!-- Admin Menu -->
                        <li class="menu-header text-xs uppercase text-gray-500 font-semibold px-2 py-2">
                            الإدارة
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('center.index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M19.7165 20.3624C21.143 19.5846 22 18.5873 22 17.5C22 16.3475 21.0372 15.2961 19.4537 14.5C17.6226 13.5794 14.9617 13 12 13C9.03833 13 6.37738 13.5794 4.54631 14.5C2.96285 15.2961 2 16.3475 2 17.5C2 18.6525 2.96285 19.7039 4.54631 20.5C6.37738 21.4206 9.03833 22 12 22C15.1066 22 17.8823 21.3625 19.7165 20.3624Z">
                                    </path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5 8.51464C5 4.9167 8.13401 2 12 2C15.866 2 19 4.9167 19 8.51464C19 12.0844 16.7658 16.2499 13.2801 17.7396C12.4675 18.0868 11.5325 18.0868 10.7199 17.7396C7.23416 16.2499 5 12.0844 5 8.51464ZM12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z">
                                    </path>
                                </svg>
                                <span>المراكز</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('stage.index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor" opacity="0.9" />
                                    <path d="M2 12l10 5 10-5" fill="currentColor" opacity="0.6" />
                                    <path d="M2 17l10 5 10-5" fill="currentColor" opacity="0.4" />
                                </svg>
                                <span>التصفيات</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('committee.index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5" d="M3 20h18v-2H3v2z" fill="currentColor" />
                                    <path d="M12 2l7 6h-4v6h-6V8H5l7-6z" fill="currentColor" />
                                </svg>
                                <span>اللجان</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('user.index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M12 12c2.2091 0 4-1.7909 4-4s-1.7909-4-4-4-4 1.7909-4 4 1.7909 4 4 4z"
                                        fill="currentColor" />
                                    <path d="M4 20c0-3.3137 3.5817-6 8-6s8 2.6863 8 6v1H4v-1z" fill="currentColor" />
                                </svg>
                                <span>مستخدم النظام</span>
                            </a>
                        </li>

                        <!-- Questions Menu -->
                        <li class="menu-header text-xs uppercase text-gray-500 font-semibold px-2 py-2 mt-4">
                            الأسئلة
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('questionset.index', 1) }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
                                        fill="currentColor" />
                                    <path d="M12 6h.01M12 12h.01M12 18h.01" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>باقات الأسئلة - حفظ وتفسير</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('questionset.index', 2) }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"
                                        fill="currentColor" />
                                    <path d="M12 6h.01M12 12h.01M12 18h.01" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>باقات الأسئلة - حفظ</span>
                            </a>
                        </li>

                        <!-- Evaluation Menu -->
                        <li class="menu-header text-xs uppercase text-gray-500 font-semibold px-2 py-2 mt-4">
                            التقييم
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('evaluation_element.index', 1) }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                        fill="currentColor" />
                                    <path d="M14 2V8H20M8 13L12 17L16 11" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>عناصر التقييم - مستوى الحفظ</span>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('evaluation_element.index', 2) }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                        fill="currentColor" />
                                    <path d="M14 2V8H20M8 13L12 17L16 11" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>عناصر التقييم - حفظ وتفسير</span>
                            </a>
                        </li>
                    @endif

                    <!-- Students Menu (Visible to organizer and admin) -->
                    @if (auth()->user()->user_type == 'organizer' || auth()->user()->user_type == 'admin')
                        <li class="menu-header text-xs uppercase text-gray-500 font-semibold px-2 py-2 mt-4">
                            المتسابقون
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('student.index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M12 12c2.2091 0 4-1.7909 4-4s-1.7909-4-4-4-4 1.7909-4 4 1.7909 4 4 4z"
                                        fill="currentColor" />
                                    <path d="M4 20c0-3.3137 3.5817-6 8-6s8 2.6863 8 6v1H4v-1z" fill="currentColor" />
                                </svg>
                                <span>المتسابقون - تسجيل الحضور</span>
                            </a>
                        </li>
                    @endif

                    <!-- Present Students (Visible to judge and admin) -->
                    @if (in_array(auth()->user()->user_type, ['judge', 'admin', 'organizer']))
                        <li class="menu-header text-xs uppercase text-gray-500 font-semibold px-2 py-2 mt-4">
                            التقييم
                        </li>

                        <li class="menu-item">
                            <a href="{{ route('student.present_index') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path opacity="0.5"
                                        d="M12 12c2.2091 0 4-1.7909 4-4s-1.7909-4-4-4-4 1.7909-4 4 1.7909 4 4 4z"
                                        fill="currentColor" />
                                    <path d="M4 20c0-3.3137 3.5817-6 8-6s8 2.6863 8 6v1H4v-1z" fill="currentColor" />
                                </svg>
                                <span>المتسابقون الحاضرون</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array(auth()->user()->user_type, ['judge']))
                        <li class="menu-item">
                            <a href="{{ route('finished_student_list') }}"
                                class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-100 text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 19h14V5H5v14zm10-12h2v10h-2V7zm-4 4h2v6h-2v-6zm-4 3h2v3H7v-3z"
                                        fill="currentColor" />
                                    <path opacity="0.5" d="M3 21h18v2H3v-2z" fill="currentColor" />
                                </svg>
                                <span>النتائج النهائية</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </div>
        </nav>

        <div class="w-full">
            <div class="p-1">
                @include('layouts._message')
            </div>
            {{ $slot }}
        </div>
    </div>

</body>

</html>
