@props(['label'])

<div {{ $attributes->merge(['class' => 'relative block text-sm font-medium text-zinc-800']) }}>
    {{ $label ?? $slot }}
</div>
