@props([
    'value' => null,
    'fallback' => '-',
])

@php
    $isFilled = filled($value);
@endphp

<div {{ $attributes->class([
    'min-h-10 px-3 py-2 text-sm rounded-md border border-zinc-200 bg-zinc-50',
    'font-mono' => ! $isFilled && $fallback === '-' && $slot->isEmpty(),
]) }}>
    @if ($slot->isEmpty())
        {{ $isFilled ? $value : $fallback }}
    @else
        {!! $slot !!}
    @endif
</div>
