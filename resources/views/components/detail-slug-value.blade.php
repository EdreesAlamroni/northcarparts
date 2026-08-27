@props([
    'slug' => null,
    'fallback' => '-',
])

@php
    $value = filled($slug) ? '/'.ltrim($slug, '/') : null;
@endphp

<x-detail-value
    :value="$value"
    :fallback="$fallback"
    dir="ltr"
    {{ $attributes->class(['font-mono']) }}
/>
