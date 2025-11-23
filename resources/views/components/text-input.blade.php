@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 h-10 border outline-none focus-visible:border-yellow-600 focus:ring-0 focus:border-yellow-600 rounded-md shadow-sm']) !!}>
