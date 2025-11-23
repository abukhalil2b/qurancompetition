{{-- resources/views/auth/forgot-password-phone.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>استعادة كلمة المرور - مساري</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .bg {
            background-image: url('/images/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-blend-mode: color-burn;
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-gray-50 text-green-900 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-[#062426] text-white bg bg-cover bg-center py-20">
        <div class="container mx-auto px-4 text-center">
            <p class="text-2xl">
                إنشاء حساب جديد
            </p>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow py-16">
        <div class="container mx-auto px-4 max-w-xl">

            {{-- Alerts --}}
            @if (session('success') || session('message'))
                <div class="mb-6 px-6 py-4 rounded-lg bg-green-100 border border-green-300 text-green-800">
                    {{ session('success') ?? session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-6 py-4 rounded-lg bg-red-100 border border-red-300 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 px-6 py-4 rounded-lg bg-red-100 border border-red-300 text-red-800">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           للتسجيل تواصل مع الإدارة
           <p>
            24644505
           </p>
        </div>
    </main>


</body>

</html>
