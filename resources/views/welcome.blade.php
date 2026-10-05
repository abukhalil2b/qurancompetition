<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسابقة مؤسسة الشيخ مستهيل للقرآن الكريم وقراءاته</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;

            /* صورة الخلفية */
            background-image: url('/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }

        /* طبقة تعتيم فوق الصورة */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(10, 30, 25, 0.55);
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 1;
            max-width: 520px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
        }


        /* العنوان */
        h1 {
            font-family: 'Amiri', serif;
            font-size: clamp(1.4rem, 4vw, 1.9rem);
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .subtitle {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            letter-spacing: 1px;
        }

        /* الأزرار */
        .actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.95rem 1.5rem;
            border-radius: 12px;
            font-family: 'Tajawal', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
        }

        .btn-primary {
            background: #fff;
            color: #0a3a2e;
        }

        .btn-primary:hover {
            background: #f0e9d8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #fff;
            transform: translateY(-2px);
        }

        form { width: 100%; }
    </style>
</head>

<body>
    <div class="card">
        <!-- الشعار -->
         @include('layouts._logo')

        <!-- العنوان -->
        <h1>مؤسسة الشيخ مستهيل<br>للقرآن الكريم وقراءاته</h1>
        <p class="subtitle">مسابقة قرآنية</p>

        <!-- الأزرار -->
        <div class="actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        الرئيسية
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline">
                            تسجيل الخروج
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        تسجيل الدخول
                    </a>
                @endauth
            @endif
        </div>
    </div>
</body>
</html>