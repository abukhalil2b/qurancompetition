@props(['class' => 'w-6 h-6'])

{{-- 8-Pointed Islamic Rosette inspired by the official competition visual identity --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M16 1.5L19.6 8.4L27.2 7.2L25 14.6L30.5 19.5L23.2 21.8L22.5 29.5L16 25.2L9.5 29.5L8.8 21.8L1.5 19.5L7 14.6L4.8 7.2L12.4 8.4L16 1.5Z"
          fill="#FFFFFF" stroke="#0F5953" stroke-width="1.4" stroke-linejoin="round"/>
    <path d="M16 5.5L18.8 11.2L25 11.2L20.8 15.8L23 21.8L16 19.2L9 21.8L11.2 15.8L7 11.2L13.2 11.2L16 5.5Z"
          fill="#1E8A7F" fill-opacity="0.22" stroke="#C9A66B" stroke-width="1.1" stroke-linejoin="round"/>
    <circle cx="16" cy="15.6" r="3.4" fill="#C9A66B" stroke="#0F5953" stroke-width="1.1"/>
    <circle cx="16" cy="15.6" r="1.2" fill="#FFFFFF"/>
</svg>
