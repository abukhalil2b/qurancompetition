<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        الجمعية العمانية للعناية بالقرآن الكريم - مركز بوشر القرآني
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 text-green-900">

    <header class="h-72 w-full bg-cover bg-center flex flex-col items-center" style="background-image: url('/assets/images/bg.png');">

  
        <!-- Logo -->
        <div class="p-1">
            @include('layouts._logo')
        </div>

        <h4 class="text-sm sm:text-sm text-green-200 "> الجمعية العمانية للعناية بالقرآن الكريم </h4>

        <h2 class="mt-2 text-4xl text-green-100">مركز بوشر القرآني</h2>

        <div class="p-3 text-orange-200">
            هدفنا تنشئة جيل قرآني متقن وحافظ لكتاب الله
        </div>

        <div class="flex gap-1">

            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="btn bg-green-200 text-green-600 border-green-600 w-44 p-3 flex items-center justify-center">الرئيسية</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn bg-red-200 text-red-600 border-red-600 w-44 p-3 flex items-center justify-center">تسجيل الخروج</button>
            </form>

            @else
            <a href="{{ route('login') }}" class="btn bg-green-200 text-green-600 border-green-600 w-44 p-3 flex items-center justify-center">
                تسجيل الدخول
            </a>

            @endauth
            @endif

        </div>
    </header>


</body>

</html>