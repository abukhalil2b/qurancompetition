<button {{ $attributes->merge(['type' => 'button', 'class' => 'flex items-center  justify-center h-8 px-3 bg-white border rounded text-xs text-black hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
