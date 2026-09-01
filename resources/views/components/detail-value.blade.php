@props([
    'value' => null,
    'fallback' => '-',
    'html' => false,
])

@php
    $isFilled = filled($value);
    $html = filter_var($html, FILTER_VALIDATE_BOOLEAN);
@endphp

<div {{ $attributes->class([
    'min-h-10 px-3 py-2 text-sm rounded-md border border-zinc-200 bg-zinc-50',
    'font-mono' => ! $isFilled && $fallback === '-' && $slot->isEmpty(),
]) }}>
    @if ($slot->isEmpty())
        @if ($html && $isFilled)
            <div class="rich-text-content">{!! $value !!}</div>
        @else
            {{ $isFilled ? $value : $fallback }}
        @endif
    @else
        {!! $slot !!}
    @endif
</div>
