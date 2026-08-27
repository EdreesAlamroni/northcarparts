@props([
    'tone' => 'zinc',
])

@php
    $tones = ['blue', 'teal', 'orange', 'sky', 'pink', 'zinc'];
    $tone = in_array($tone, $tones, true) ? $tone : 'zinc';
@endphp

<div @class(["dashboard-skeleton dashboard-skeleton--{$tone}", 'dashboard-skeleton-card p-5 lg:p-6'])>
    <div class="space-y-4">
        <div @class(['dashboard-skeleton__title', 'h-5 w-40 animate-pulse rounded'])></div>
        <div @class(['dashboard-skeleton__subtitle', 'h-3 w-56 animate-pulse rounded'])></div>
        <div @class(['dashboard-skeleton__body', 'h-64 animate-pulse rounded-lg'])></div>
    </div>
</div>
