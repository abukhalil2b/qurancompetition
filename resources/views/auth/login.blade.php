<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="simple-login-container">
        <div class="simple-login-card">
            
            <!-- عنوان بسيط -->
            <div class="simple-login-header flex justify-center items-center flex-col">
                 @include('layouts._logo')
     
                <h1 class="simple-title">فاستمسك</h1>
                <p class="simple-subtitle">المسابقة القرآنية</p>
            </div>

            <!-- نموذج تسجيل الدخول -->
            <form method="POST" action="{{ route('login') }}" class="simple-login-form">
                @csrf
                
                <!-- حقل الرقم المدني -->
                <div class="simple-input-group">
                    <label for="national_id" class="simple-label">الرقم المدني</label>
                    <input 
                        id="national_id" 
                        class="simple-input" 
                        type="text" 
                        name="national_id" 
                        value="{{ old('national_id') }}" 
                        required 
                        autofocus
                        placeholder="أدخل الرقم المدني">
                    @error('national_id')
                        <p class="simple-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- حقل كلمة المرور -->
                <div class="simple-input-group">
                    <div class="simple-label-row">
                        <label for="password" class="simple-label">كلمة المرور</label>
                        <button type="button" class="simple-toggle-btn" onclick="togglePassword()">
                            <span id="toggleText">عرض</span>
                        </button>
                    </div>
                    <input 
                        id="password" 
                        class="simple-input" 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="أدخل كلمة المرور">
                    @error('password')
                        <p class="simple-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- زر تسجيل الدخول -->
                <div class="simple-button-container">
                    <button type="submit" class="simple-submit-btn">
                        تسجيل الدخول
                    </button>
                </div>
                
                <!-- رابط إضافي -->
                @if (Route::has('password.request'))
                <div class="simple-form-footer">
                    <a href="{{ route('password.request') }}" class="simple-link">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var toggleText = document.getElementById("toggleText");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleText.textContent = "إخفاء";
            } else {
                passwordInput.type = "password";
                toggleText.textContent = "عرض";
            }
        }
    </script>
    
    <style>
        /* الأساسيات */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
        body {
            background-color: #f0f9f0; /* أخضر فاتح جداً */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* الحاوية الرئيسية */
        .simple-login-container {
            width: 100%;
            max-width: 400px;
        }
        
        /* البطاقة */
        .simple-login-card {
            background-color: white;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 8px 24px rgba(0, 100, 0, 0.08);
            border: 1px solid #e0f0e0;
        }
        
        /* العنوان */
        .simple-login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .simple-title {
            color: #b8860b; /* ذهبي */
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .simple-subtitle {
            color: #2e8b57; /* أخضر متوسط */
            font-size: 18px;
            font-weight: 500;
        }
        
        /* النموذج */
        .simple-login-form {
            width: 100%;
        }
        
        /* مجموعة الإدخال */
        .simple-input-group {
            margin-bottom: 24px;
        }
        
        /* التسمية */
        .simple-label {
            display: block;
            color: #2e8b57; /* أخضر متوسط */
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .simple-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        /* زر العرض */
        .simple-toggle-btn {
            background: none;
            border: none;
            color: #b8860b; /* ذهبي */
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        
        .simple-toggle-btn:hover {
            background-color: rgba(184, 134, 11, 0.08);
        }
        
        /* حقل الإدخال */
        .simple-input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #d0e8d0;
            border-radius: 10px;
            font-size: 16px;
            color: #333;
            background-color: #fafffa;
            transition: all 0.3s;
        }
        
        .simple-input:focus {
            outline: none;
            border-color: #b8860b; /* ذهبي */
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
            background-color: white;
        }
        
        .simple-input::placeholder {
            color: #a0c0a0;
        }
        
        /* رسائل الخطأ */
        .simple-error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 6px;
        }
        
        /* زر تسجيل الدخول */
        .simple-button-container {
            margin-top: 32px;
        }
        
        .simple-submit-btn {
            width: 100%;
            background-color: #b8860b; /* ذهبي */
            color: white;
            border: none;
            border-radius: 10px;
            padding: 16px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .simple-submit-btn:hover {
            background-color: #a0780a; /* ذهبي أغمق قليلاً */
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(184, 134, 11, 0.2);
        }
        
        .simple-submit-btn:active {
            transform: translateY(0);
        }
        
        /* رابط نسيت كلمة المرور */
        .simple-form-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e8f4e8;
        }
        
        .simple-link {
            color: #2e8b57; /* أخضر متوسط */
            text-decoration: none;
            font-size: 15px;
            transition: color 0.2s;
        }
        
        .simple-link:hover {
            color: #b8860b; /* ذهبي */
            text-decoration: underline;
        }
        
        /* تصميم متجاوب */
        @media (max-width: 480px) {
            .simple-login-card {
                padding: 32px 24px;
            }
            
            .simple-title {
                font-size: 32px;
            }
            
            .simple-subtitle {
                font-size: 16px;
            }
            
            .simple-input {
                padding: 12px 14px;
            }
            
            .simple-submit-btn {
                padding: 14px;
            }
        }
        
        @media (max-width: 360px) {
            .simple-login-card {
                padding: 24px 20px;
            }
            
            .simple-title {
                font-size: 28px;
            }
        }
        
        /* تأثيرات بسيطة */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .simple-login-card {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</x-guest-layout>