@props([
    'tone' => 'zinc',
    'count' => 2,
])

@php
    $tones = ['blue', 'teal', 'orange', 'sky', 'pink', 'zinc'];
    $tone = in_array($tone, $tones, true) ? $tone : 'zinc';
@endphp

<div @class(["dashboard-skeleton dashboard-skeleton--{$tone}", 'grid auto-rows-min gap-4 sm:gap-5', $count === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2'])>
    @foreach (range(1, $count) as $index)
        <div class="dashboard-skeleton-card p-5 lg:p-6">
            <div class="space-y-3">
                <div @class(['dashboard-skeleton__title', 'h-7 w-7 animate-pulse rounded-lg'])></div>
                <div @class(['dashboard-skeleton__subtitle', 'h-4 w-32 animate-pulse rounded'])></div>
                <div @class(['dashboard-skeleton__body', 'h-12 animate-pulse rounded-lg'])></div>
            </div>
        </div>
    @endforeach
</div>
