<button {{ $attributes->merge(['type' => 'submit', 'class' => 'flex items-center justify-center h-8 px-3 bg-red-600 border rounded text-xs border-red-700 text-white hover:bg-red-400 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
