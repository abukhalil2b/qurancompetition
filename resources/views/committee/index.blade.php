<x-app-layout>

    <div class="p-6 space-y-6">
        <h1 class="text-2xl font-bold">اللجان</h1>

        {{-- Committees List --}}
        <div class="bg-white shadow rounded-lg p-4">

            <table class="w-full text-right">
                <thead>
                    <tr class="border-b">
                        <th>اللجنة</th>
                        <th>الأعضاء</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($committees as $committee)
                        <tr class="border-b">
                            <td>
                               <p class="text-xl text-blue-800"> {{ $committee->title }}</p>
                               <p class="text-xs text-blue-800"> {{ __($committee->gender) }}</p>
                                <p class="text-xs">
                                    {{ $committee->center->title }}
                                </p>
                            </td>
                            <td>
                                @foreach($committee->judges as $judge)
                                <div class="text-xs">
                                    {{ $judge->name }}
                                </div>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-2 text-center text-gray-500">لا توجد لجان مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Create Form --}}
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-3">إضافة لجنة جديدة</h2>

            <form action="{{ route('committee.store') }}" method="POST" >
                @csrf

                <div class="mb-4">
                    <label class="font-semibold">اسم اللجنة</label>
                    <input type="text" name="title" class="w-full border rounded p-2" required>
                </div>

              <div class="flex gap-2">
                  <div>
                    <label class="font-semibold">الجنس/نوع اللجنة</label>
                    <select name="gender" class="w-44 border rounded p-2" required>
                         <option value="males">{{ __('males') }}</option>
                         <option value="females">{{ __('females') }}</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold">المركز</label>
                    <select name="center_id" class="w-44 border rounded p-2" required>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->title }}</option>
                        @endforeach
                    </select>
                </div>
              </div>

                <div class="col-span-2 text-left">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">حفظ</button>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>
