<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-brand-danger']) }}>
    {{ $slot }}
</button>
