@props([
    'icon',
    'label',
    'value',
    'tone' => 'blue',
])

@php
    $tones = ['blue', 'green', 'purple', 'teal', 'orange', 'sky', 'pink'];
    $tone = in_array($tone, $tones, true) ? $tone : 'blue';
@endphp

<x-card @class(["dashboard-stat-card", "dashboard-stat-card--{$tone}"])>
    <x-slot:slot class="space-y-3 p-5 lg:p-6">
        <span @class(["dashboard-stat-icon--{$tone}"])>
            <flux:icon :name="$icon" class="h-4 w-4 shrink-0" />
        </span>

        <p class="text-sm text-zinc-500">{{ $label }}</p>

        <p class="text-2xl font-medium font-mono tabular-nums text-zinc-900">{{ number_format($value) }}</p>
    </x-slot:slot>
</x-card>
