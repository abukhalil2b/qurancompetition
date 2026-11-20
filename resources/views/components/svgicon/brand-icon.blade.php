@props(['name' => 'home', 'class' => 'w-5 h-5'])

@switch($name)
    @case('home')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 10.5L12 3l9 7.5" />
            <path d="M5 9.5V20a1 1 0 001 1h12a1 1 0 001-1V9.5" fill="currentColor" fill-opacity="0.14" />
            <path d="M9.5 21v-6a2.5 2.5 0 015 0v6" />
        </svg>
        @break

    @case('stages')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="12 2.5 21 7.2 12 12 3 7.2 12 2.5" fill="currentColor" fill-opacity="0.2" />
            <polyline points="3 12 12 16.8 21 12" />
            <polyline points="3 16.8 12 21.5 21 16.8" />
        </svg>
        @break

    @case('centers')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="currentColor" fill-opacity="0.18" />
            <circle cx="12" cy="9" r="2.5" />
        </svg>
        @break

    @case('committees')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 21h16" />
            <path d="M6 21V11a6 6 0 0112 0v10" fill="currentColor" fill-opacity="0.16" />
            <path d="M12 2v3" />
            <path d="M10 21v-5a2 2 0 014 0v5" />
            <circle cx="12" cy="5" r="1" fill="currentColor" />
        </svg>
        @break

    @case('users')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9" cy="7.5" r="3.5" fill="currentColor" fill-opacity="0.18" />
            <path d="M2.5 20a6.5 6.5 0 0113 0" />
            <circle cx="17" cy="8.5" r="2.5" />
            <path d="M17.5 14a5 5 0 014 4.9" />
        </svg>
        @break

    @case('students')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 3L2 8l10 5 10-5-10-5z" fill="currentColor" fill-opacity="0.18" />
            <path d="M6 10.5v5.2c0 1.4 2.7 2.8 6 2.8s6-1.4 6-2.8v-5.2" />
            <path d="M22 9v6" />
        </svg>
        @break

    @case('evaluation')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="4" y="3" width="16" height="18" rx="2.5" fill="currentColor" fill-opacity="0.15" />
            <path d="M8.5 9.5l2 2 5-5" />
            <path d="M8.5 15.5h7" />
        </svg>
        @break

    @case('questions')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 5.5A2.5 2.5 0 016.5 3H12v17H6.5A2.5 2.5 0 004 22V5.5z" fill="currentColor" fill-opacity="0.15" />
            <path d="M20 5.5A2.5 2.5 0 0017.5 3H12v17h5.5a2.5 2.5 0 012.5 2V5.5z" fill="currentColor" fill-opacity="0.1" />
            <path d="M7.5 8h2M7.5 11.5h2M14.5 8h2M14.5 11.5h2" />
        </svg>
        @break

    @case('attendance')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="9.5" cy="7.5" r="3.5" fill="currentColor" fill-opacity="0.18" />
            <path d="M3 19.5a6.5 6.5 0 0113 0" />
            <path d="M16.5 12.5l2 2 4-4" />
        </svg>
        @break

    @case('present')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" fill="currentColor" fill-opacity="0.14" />
            <rect x="9" y="3" width="6" height="4" rx="1" />
            <path d="M9 12l2 2 4-4" />
            <path d="M9 17h6" />
        </svg>
        @break

    @case('results')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M8 21h8" />
            <path d="M12 17v4" />
            <path d="M7 4h10v6a5 5 0 01-10 0V4z" fill="currentColor" fill-opacity="0.18" />
            <path d="M7 6H4v2a3 3 0 003 3" />
            <path d="M17 6h3v2a3 3 0 01-3 3" />
        </svg>
        @break

    @case('statistics')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="12" width="4" height="8" rx="1" fill="currentColor" fill-opacity="0.2" />
            <rect x="10" y="7" width="4" height="13" rx="1" fill="currentColor" fill-opacity="0.2" />
            <rect x="17" y="4" width="4" height="16" rx="1" fill="currentColor" fill-opacity="0.2" />
            <path d="M2 20h20" />
        </svg>
        @break

    @case('guide')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 3h11a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" fill="currentColor" fill-opacity="0.15" />
            <path d="M8 7h8M8 11h8M8 15h5" />
            <path d="M15 17l2 2 3-3" />
        </svg>
        @break

    @case('quran')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2 6.5C2 5.4 2.9 4.5 4 4.5h4.5c2 0 3.5 1.1 3.5 2.7 0-1.6 1.5-2.7 3.5-2.7H20c1.1 0 2 .9 2 2v11c0 1.1-.9 2-2 2h-4.5c-1.8 0-3.5.8-3.5 2 0-1.2-1.7-2-3.5-2H4c-1.1 0-2-.9-2-2v-11z" fill="currentColor" fill-opacity="0.16" />
            <path d="M12 7.2V21.5" />
        </svg>
        @break

    @case('print')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 9V4h12v5" />
            <rect x="4" y="9" width="16" height="8" rx="2" fill="currentColor" fill-opacity="0.15" />
            <path d="M6 14h12v6H6v-6z" fill="currentColor" fill-opacity="0.1" />
        </svg>
        @break

    @case('excel')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" fill="currentColor" fill-opacity="0.15" />
            <polyline points="14 2 14 8 20 8" />
            <path d="M12 11v6m0 0l-2.5-2.5M12 17l2.5-2.5" />
        </svg>
        @break

    @case('logout')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        @break

    @case('id-card')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="5" width="18" height="14" rx="2.5" fill="currentColor" fill-opacity="0.14" />
            <circle cx="9" cy="11" r="2" />
            <path d="M6.5 16a3 3 0 015 0" />
            <path d="M14 10h4M14 14h3" />
        </svg>
        @break

    @case('lock')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="4" y="11" width="16" height="10" rx="2.5" fill="currentColor" fill-opacity="0.16" />
            <path d="M8 11V7a4 4 0 018 0v4" />
            <circle cx="12" cy="16" r="1.2" fill="currentColor" />
        </svg>
        @break

    @case('refresh')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 12a9 9 0 11-2.64-6.36" />
            <polyline points="21 3 21 9 15 9" />
        </svg>
        @break

    @case('plus')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9" fill="currentColor" fill-opacity="0.15" />
            <path d="M12 8v8M8 12h8" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9" fill="currentColor" fill-opacity="0.14" />
            <circle cx="12" cy="12" r="3" />
        </svg>
@endswitch
