@props([
    'title',
    'tone' => 'blue',
])

@php
    $tones = ['blue', 'green', 'purple', 'teal', 'orange', 'sky', 'pink'];
    $tone = in_array($tone, $tones, true) ? $tone : 'blue';
@endphp

<div {{ $attributes->class(['dashboard-section-heading']) }}>
    <span @class(["dashboard-section-heading__accent--{$tone}"])" aria-hidden="true"></span>
    <flux:heading class="font-medium text-zinc-900">{{ $title }}</flux:heading>
</div>
