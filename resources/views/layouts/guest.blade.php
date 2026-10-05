<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
   
</head>

<body>

    
    <!-- المحتوى الرئيسي -->
    <main class="main-content">
        {{ $slot }}
    </main>
 
</body>
</html>