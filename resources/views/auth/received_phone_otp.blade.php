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

    <!-- Content -->
    <main class="flex-grow py-16">
        <div class="container mx-auto px-4 max-w-xl">

            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-100 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-700 bg-red-100 p-4 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('verify_phone_otp') }}"
                class="bg-white shadow-md rounded-lg p-8 border border-gray-200">
                @csrf

                <input type="hidden" name="phone" value="{{ old('phone', $phone ?? '') }}">

                <label for="otp" class="block text-lg font-bold mb-3">رمز التحقق (OTP)</label>
                <input id="otp" name="otp" type="text" required dir="ltr"
                    class="w-full border border-gray-300 rounded px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-green-700 focus:border-green-700 placeholder:text-sm"
                    placeholder="أدخل رمز التحقق المرسل" />

                <button type="submit"
                    class="mt-6 w-full bg-green-700 text-white py-3 rounded-lg text-lg hover:bg-opacity-90 transition-all duration-200">
                    تأكيد الرمز
                </button>
            </form>
        </div>
    </main>


</body>

</html>
