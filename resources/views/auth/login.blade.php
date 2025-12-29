<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-96 m-auto py-5">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- national_id Address -->
            <div>
                <label for="national_id"> الرقم المدني</label>
                <x-text-input class="block mt-1 w-full" type="number" name="national_id" :value="old('national_id')" required
                    autofocus />
                <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <div class="flex justify-between items-center">
                    <label for="password"> كلمة المرور </label>
                    <button type="button" onclick="togglePassword()">عرض كلمة المرور</button>
                </div>
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>


            <div class="flex items-center justify-between mt-4">
                <x-primary-button class="ml-3">
                    تسجيل الدخول
                </x-primary-button>
            </div>
        </form>
    </div>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</x-guest-layout>
