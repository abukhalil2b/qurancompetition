<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#065f46">

    <title>الجمعية العمانية للعناية بالقرآن الكريم - مركز بوشر القرآني</title>
    <link href="https://fonts.googleapis.com/css?family=Tajawal&amp;display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nav-link:hover svg {
            transform: translateY(-3px);
            transition: transform 0.2s ease;
        }

        .avatar-hover:hover {
            box-shadow: 0 0 0 3px rgba(6, 95, 70, 0.3);
            transition: all 0.3s ease;
        }

        .footer-wave {
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            height: 20px;
            background-size: cover;
        }

        .active-nav {
            background-color: #065f46;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50 text-green-900 font-neo antialiased">

    <!-- header -->
    <header class="h-24 py-2 w-full bg-cover bg-center flex flex-col items-center justify-center shadow-md"
        style="background-image:url('/assets/images/bg.png');">
        <div class="text-center">
            <h4 class="text-sm sm:text-base text-green-100 tracking-wide">الجمعية العمانية للعناية بالقرآن الكريم</h4>
            <h2 class="mt-1 text-2xl md:text-3xl text-white font-bold">مركز بوشر القرآني</h2>
        </div>
    </header>
<div class="p-3 text-xs">
    @include('layouts._message')
</div>
    <div class="mt-4 py-1 flex gap-1 justify-center px-4">
        <div class="w-full max-w-7xl">
            <!-- User Profile Card -->
            <div
                class="w-full p-2 bg-white rounded-lg shadow-sm flex items-center justify-between gap-2 border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3">
                    <div class="avatar-hover relative">
                        <img class="w-14 h-14 rounded-full border-2 border-green-100 object-cover"
                            src="/assets/images/avatar/avatar.png" alt="avatar">
                        <div class="absolute -bottom-1 -right-1 bg-green-600 rounded-full p-1">
                            <x-svgicon.user-circle class="h-4 w-4 text-white" />
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1">
                            <x-svgicon.clock class="h-3 w-3" />
                            {{ auth()->user()->national_id }}
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 px-3 py-1 rounded-full hover:bg-red-50 transition-colors">
                        <x-svgicon.logout class="h-4 w-4" />
                        تسجيل الخروج
                    </button>
                </form>
            </div>

            <!-- Navigation Menu -->
            <div class="w-full mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2">
                <a href="/dashboard"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.dashboard />
                    <span class="text-xs">الرئيسية</span>
                </a>

                <a href="/profile"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.profile />
                    <span class="text-xs">الملف الشخصي</span>
                </a>

                <a href="{{ url('/') }}"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.book />
                    <span class="text-xs">الدورات المتوفرة</span>
                </a>

                <a href="/my_courses"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.my-courses />
                    <span class="text-xs">دوراتي</span>
                </a>

                <a href="/message"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.chat />
                    <span class="text-xs">التواصل</span>
                </a>

                <a href="/my_payments"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.payments />
                    <span class="text-xs">سجل الدفع</span>
                </a>

                <a href="/my_invoices"
                    class="nav-link text-green-800 border border-green-200 rounded-lg p-2 flex flex-col justify-center items-center bg-white hover:bg-green-50 hover:border-green-300 hover:shadow-sm transition-all">
                    <x-svgicon.invoices />
                    <span class="text-xs">الفواتير</span>
                </a>
            </div>
        </div>
    </div>

    {{ $slot }}

    <div class="mt-16"></div>

    <!-- Footer with wave effect -->
    <footer class="w-full bg-gray-800 text-white relative mt-12">
        <div class="footer-wave"></div>
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 text-center md:text-right">
                    <h3 class="text-lg font-semibold mb-2">مركز بوشر القرآني</h3>
                    <p class="text-gray-300 text-sm">الجمعية العمانية للعناية بالقرآن الكريم</p>
                </div>
                <div class="flex space-x-4 space-x-reverse">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <x-svgicon.facebook />
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <x-svgicon.instagram />
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <x-svgicon.twitter />
                    </a>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-700 text-center text-sm text-gray-400">
                <p>© 2025 مركز بوشر القرآني. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>


    <script>
        // Add active class to current page link
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active-nav');
                    link.classList.remove('border-green-200', 'hover:border-green-300');
                    link.classList.add('border-green-600');

                    // Change icon color to white in active state
                    const icon = link.querySelector('svg');
                    if (icon) {
                        icon.classList.remove('text-green-600');
                        icon.classList.add('text-white');
                    }
                }
            });
        });
    </script>
</body>

</html>
