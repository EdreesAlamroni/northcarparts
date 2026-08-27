@props([
    'value' => null,
    'fallback' => '-',
])

@php
    $isFilled = filled($value);
@endphp

<span {{ $attributes->class([
    'font-mono' => ! $isFilled && $fallback === '-',
]) }}>
    {{ $isFilled ? $value : $fallback }}
</span>
