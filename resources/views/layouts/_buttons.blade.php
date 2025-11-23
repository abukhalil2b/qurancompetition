<div class="mt-2 py-1 flex gap-1 justify-center">

    @if (Route::has('login'))
    <div>
        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
            تسجيل الدخول
        </a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn btn-sm btn-secondary text-[10px]">
            تسجيل حساب جديد
        </a>
        @endif
    </div>
    @endif

</div>