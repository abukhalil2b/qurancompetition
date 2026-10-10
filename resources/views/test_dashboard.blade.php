<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المستخدمون</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: "Tajawal", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="max-w-6xl mx-auto px-4 py-10"
     x-data="{
        search: '',
     }">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">المستخدمون</h1>
            <p class="text-sm text-gray-500 mt-1">
                إدارة الحسابات والدخول كمستخدم
            </p>
        </div>

        <div class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-lg">
            {{ $users->count() }} مستخدم
        </div>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input
            type="text"
            x-model="search"
            placeholder="ابحث بالاسم أو الصلاحية..."
            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
    </div>

    <!-- Grid -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($users as $user)
            <div
                x-show="
                    ('{{ strtolower($user->full_name . ' ' . ($user->activeRole->name ?? '')) }}')
                    .includes(search.toLowerCase())
                "
                class="bg-white rounded-2xl border border-gray-100 p-4 flex flex-col justify-between hover:shadow-md transition"
            >

                <!-- Top -->
                <div class="flex items-center gap-3 mb-4">

                    <!-- Avatar -->
                    <div class="w-11 h-11 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>

                    <!-- Info -->
                    <div>
                        <div class="font-semibold text-sm">
                            {{ $user->name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            صلاحية: {{ $user->role ?? 'بدون صلاحية' }}
                        </div>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="flex items-center justify-between mt-auto">

                    <span class="text-xs text-gray-400">
                        #{{ $user->id }}
                    </span>

                    <a href="{{ route('test_login_to_user_account', $user->id) }}"
                       class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        دخول
                    </a>

                </div>

            </div>
        @empty
            <div class="col-span-full text-center text-gray-400 py-10">
                لا يوجد مستخدمون
            </div>
        @endforelse

    </div>

</div>

</body>
</html>