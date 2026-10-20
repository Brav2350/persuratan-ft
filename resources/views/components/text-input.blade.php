@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 focus:border-primary-focus focus:ring-primary-focus rounded-md shadow-sm']) }}>
