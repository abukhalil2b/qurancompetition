@if (session('message'))
    @php
        $status = session('status', 'success');
        $isSuccess = $status === 'success';
    @endphp
    <div class="my-3 px-4 py-3.5 rounded-xl border flex items-center justify-center gap-2.5 text-sm font-semibold shadow-sm
        {{ $isSuccess ? 'bg-brand-teal-50 border-brand-teal-200 text-brand-teal-900' : 'bg-rose-50 border-rose-200 text-rose-800' }}">
        <svg class="w-5 h-5 shrink-0 {{ $isSuccess ? 'text-brand-teal-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            @if($isSuccess)
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @endif
        </svg>
        <span>{{ session('message') }}</span>
    </div>
@endif

@if (session('success'))
    <div class="bg-brand-teal-50 border border-brand-teal-200 border-r-4 border-r-brand-teal-600 text-brand-teal-900 px-4 py-3.5 rounded-xl flex items-center gap-3 mb-4 shadow-sm" role="alert">
        <svg class="w-5 h-5 text-brand-teal-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bg-rose-50 border border-rose-200 border-r-4 border-r-rose-600 text-rose-800 px-4 py-3.5 rounded-xl flex items-center gap-3 mb-4 shadow-sm" role="alert">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="bg-brand-gold-50 border border-brand-gold-300 border-r-4 border-r-brand-gold-500 text-brand-gold-900 px-4 py-3.5 rounded-xl flex items-center gap-3 mb-4 shadow-sm" role="alert">
        <svg class="w-5 h-5 text-brand-gold-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-7.1 12.26A2 2 0 004.92 19h14.16a2 2 0 001.73-2.88l-7.1-12.26a2 2 0 00-3.42 0z"/>
        </svg>
        <span class="text-sm font-semibold">{{ session('warning') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 border-r-4 border-r-rose-600 text-rose-800 px-4 py-3.5 rounded-xl mb-4 shadow-sm" role="alert">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('info'))
    <div class="bg-brand-teal-50/80 border border-brand-teal-200 border-r-4 border-r-brand-gold-400 text-brand-teal-900 px-4 py-3.5 rounded-xl flex items-center gap-3 mb-4 shadow-sm" role="alert">
        <svg class="w-5 h-5 text-brand-teal-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-semibold">{{ session('info') }}</span>
    </div>
@endif
