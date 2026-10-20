<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary-hover border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-pressed focus:bg-gray-700 active:bg-primary-base focus:outline-none focus:ring-2 focus:ring-primary-focus focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
