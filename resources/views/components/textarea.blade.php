@props(['disabled' => false,'value'=>''])
<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 border outline-none focus-visible:border-yellow-600 focus:ring-0 focus:border-yellow-600 rounded-md shadow-sm h-30 w-full']) !!}>{{ $value }}</textarea>
