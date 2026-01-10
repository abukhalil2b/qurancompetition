<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المسابقة القرآنية فاستمسك</title>
    
    <!-- إضافة خط عربي إسلامي -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Reem+Kufi:wght@400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --islamic-green: #0a5c36;
            --islamic-gold: #c19a53;
            --islamic-blue: #1d5a7c;
            --light-beige: #f5f1e8;
            --dark-beige: #e8dfcc;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--light-beige);
            color: #2c3e50;
            font-family: 'Amiri', serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* زخرفة إسلامية في الخلفية */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(193, 154, 83, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 80% 20%, rgba(10, 92, 54, 0.05) 0%, transparent 20%);
            pointer-events: none;
            z-index: -1;
        }
        
        /* عناصر زخرفية إسلامية */
        .islamic-border {
            border: 2px solid var(--islamic-gold);
            border-radius: 8px;
            position: relative;
        }
        
        .islamic-border::before,
        .islamic-border::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid var(--islamic-gold);
            border-radius: 50%;
        }
        
        .islamic-border::before {
            top: -10px;
            left: -10px;
        }
        
        .islamic-border::after {
            bottom: -10px;
            right: -10px;
        }
        
        .header-container {
            background: linear-gradient(to bottom, var(--islamic-green), #0a472a);
            min-height: 72px;
            width: 100%;
            position: relative;
            overflow: hidden;
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* نمط إسلامي للرأس */
        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(193, 154, 83, 0.1) 10px, rgba(193, 154, 83, 0.1) 20px);
            opacity: 0.3;
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        /* تحسين الشعار */
        .logo-container {
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--islamic-gold);
        }
        
        /* تحسين الأزرار */
        .btn {
            font-family: 'Reem Kufi', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            border: 2px solid;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-login {
            background-color: rgba(234, 247, 240, 0.9);
            color: var(--islamic-green);
            border-color: var(--islamic-green);
        }
        
        .btn-dashboard {
            background-color: rgba(255, 248, 225, 0.9);
            color: #8b6914;
            border-color: var(--islamic-gold);
        }
        
        .btn-logout {
            background-color: rgba(255, 235, 235, 0.9);
            color: #a83a3a;
            border-color: #c53030;
        }
        
        .buttons-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1rem;
        }
        
        /* تصميم العنوان */
        .page-title {
            text-align: center;
            color: white;
            margin: 0.5rem 0 1.5rem;
            font-family: 'Reem Kufi', sans-serif;
            font-size: 2.2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .page-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 120px;
            height: 3px;
            background: linear-gradient(to right, transparent, var(--islamic-gold), transparent);
        }
        
        /* آية قرآنية */
        .quran-verse {
            text-align: center;
            color: white;
            font-size: 1.2rem;
            margin: 1rem 0 2rem;
            font-style: italic;
            line-height: 1.8;
            max-width: 800px;
            padding: 0 1rem;
        }
        
        /* تأثيرات للهاتف */
        @media (max-width: 768px) {
            .buttons-container {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 280px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .quran-verse {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 480px) {
            .btn {
                width: 240px;
                font-size: 1rem;
                padding: 0.6rem 1.2rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
        
        /* عنصر زخرفي إسلامي */
        .islamic-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath d='M50,10 C70,10 85,25 85,45 C85,65 70,80 50,80 C30,80 15,65 15,45 C15,25 30,10 50,10 Z' fill='none' stroke='%23c19a53' stroke-width='3'/%3E%3Cpath d='M50,20 C66,20 78,32 78,48 C78,64 66,76 50,76 C34,76 22,64 22,48 C22,32 34,20 50,20 Z' fill='none' stroke='%23c19a53' stroke-width='2'/%3E%3C/svg%3E");
            background-size: contain;
            opacity: 0.6;
            z-index: 0;
        }
        
        .decoration-1 {
            top: 20px;
            left: 5%;
        }
        
        .decoration-2 {
            bottom: 20px;
            right: 5%;
            transform: rotate(45deg);
        }
    </style>
</head>

<body>
    <!-- العناصر الزخرفية -->
    <div class="islamic-decoration decoration-1"></div>
    <div class="islamic-decoration decoration-2"></div>

    <header class="header-container">
        <div class="header-pattern"></div>
        
        <div class="header-content">
            <!-- العنوان -->
            <h1 class="page-title">المسابقة القرآنية فاستمسك</h1>
            
            <!-- آية قرآنية -->
            <div class="quran-verse">
               ﴿ وَالَّذِينَ يُمَسِّكُونَ بِالْكِتَابِ وَأَقَامُوا الصَّلَاةَ إِنَّا لَا نُضِيعُ أَجْرَ الْمُصْلِحِينَ﴾ 
            </div>
            
            <!-- الشعار -->
            <div class="logo-container islamic-border">
                @include('layouts._logo')
            </div>
            
            <!-- الأزرار -->
            <div class="buttons-container">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-dashboard">
                            <span>الرئيسية</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <span>تسجيل الدخول</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

</body>
</html>