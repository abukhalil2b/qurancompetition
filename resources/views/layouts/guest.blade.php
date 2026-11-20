<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-islamic-pattern font-tajawal text-brand-teal-950 antialiased min-h-screen">
    <main class="main-content min-h-screen">
        {{ $slot }}
    </main>
</body>

</html>
