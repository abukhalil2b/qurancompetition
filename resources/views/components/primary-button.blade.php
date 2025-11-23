<button {{ $attributes->merge(['type' => 'submit', 'class' => 'flex items-center  justify-center h-8 px-3 bg-[#e3ccb4] border rounded text-xs border-yellow-800 text-yellow-800 hover:bg-[#f1e1cf] focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
