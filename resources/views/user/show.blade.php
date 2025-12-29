<x-app-layout>
    <div class="p-4 md:p-6 max-w-5xl mx-auto space-y-6" dir="rtl">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-500 text-sm">إدارة بيانات وصلاحيات المستخدم</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100">
                    {{ __($user->user_type) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Right Column: User Info Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-blue-600 rounded-full"></span>
                        بيانات المستخدم
                    </h2>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">النوع/الجنس</span>
                            <span class="font-medium">{{ __($user->gender) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">الرقم المدني</span>
                            <span class="font-mono font-medium">{{ $user->national_id }}</span>
                        </div>
                    </div>
                </div>

                {{-- Connected Committees Summary --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-green-600 rounded-full"></span>
                        اللجان المرتبطة
                    </h2>
                    
                    <div class="space-y-3">
                        @forelse($user->committees as $committee)
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="font-bold text-gray-800 text-sm">
                                    {{ $committee->title }} ({{ __($committee->gender) }})
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $committee->center->title }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-400 text-sm">لا توجد ارتباطات حالية</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Left Column: Assignment Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900">إدارة ربط اللجان</h2>
                        <p class="text-sm text-gray-500">قم بتحديد اللجان التي ترغب في تعيين المستخدم إليها</p>
                    </div>

                    <form action="{{ route('judge.assign_committee') }}" method="POST">
                        @csrf
                        <input type="hidden" name="judge_id" value="{{ $user->id }}">

                        <div class="space-y-6">
                            @php
                                $groupedCommittees = $committees->groupBy('center.title');
                            @endphp

                            @foreach ($groupedCommittees as $centerName => $centerCommittees)
                                <div>
                                    <h3 class="text-sm font-bold text-gray-400 mb-3 bg-gray-50 p-2 rounded uppercase tracking-wider">
                                        {{ $centerName }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach ($centerCommittees as $committee)
                                            <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 transition-all group">
                                                <input type="checkbox" name="committee_ids[]" value="{{ $committee->id }}"
                                                    @checked($user->committees->contains($committee->id))
                                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                
                                                <div class="mr-3">
                                                    <span class="block text-sm font-bold text-gray-700 group-hover:text-blue-900">
                                                        {{ $committee->title }}
                                                    </span>
                                                    <span class="block text-[10px] text-gray-500 uppercase font-medium">
                                                        {{ __($committee->gender) }}
                                                    </span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-200 transition-all focus:ring-4 focus:ring-blue-100">
                                حفظ التغييرات والربط
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>