<x-app-layout>

    <div class="p-6 space-y-6">

        {{-- Judge Info --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-bold">بيانات المستخدم</h2>
            <p class="mt-2"><strong>الاسم:</strong> {{ $user->name }}</p>
            <p class="mt-2"><strong>النوع/الجنس:</strong> {{ __($user->gender) }}</p>
            <p class="mt-2"><strong>نوع المستخدم/الصلاحيات:</strong> {{ __($user->user_type) }}</p>
            <p><strong>الرقم المدني:</strong> {{ $user->national_id }}</p>
        </div>


        {{-- Committees Assigned --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-3">اللجان المرتبط بها المحكم</h2>

            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th>اللجنة</th>
                        <th>المركز</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->committees as $committee)
                        <tr class="border-b">
                            <td>
                                {{ $committee->title }}
                                {{ __($committee->gender) }}
                            </td>
                            <td>{{ $committee->center->title }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-2">
                                لا يوجد ارتباطات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- Assign to Committees --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-3">ربط المحكّم باللجان</h2>

            <form action="{{ route('judge.assign_committee') }}" method="POST" class="space-y-4">
                @csrf

                <input type="hidden" name="judge_id" value="{{ $user->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    @foreach ($committees as $committee)
                        <label
                            class="flex items-center space-x-2 rtl:space-x-reverse border p-2 rounded cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="committee_ids[]" value="{{ $committee->id }}"
                                @checked($user->committees->contains($committee->id))>

                            <span>{{ $committee->title }} - {{ __($committee->gender) }} - ({{ $committee->center->title }})</span>
                        </label>
                    @endforeach

                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    حفظ الربط
                </button>
            </form>
        </div>


    </div>

</x-app-layout>
