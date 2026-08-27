@props([
    'tone' => 'blue',
    'icon' => null,
    'title' => null,
    'description' => null,
])

@php
    $tones = ['blue', 'green', 'purple', 'teal', 'orange', 'sky', 'pink'];
    $tone = in_array($tone, $tones, true) ? $tone : 'blue';
@endphp

<x-card @class([
    'dashboard-panel-card',
    "dashboard-panel-card--{$tone}",
])>
    @if (isset($heading))
        <x-slot:heading class="dashboard-panel-heading">{{ $heading }}</x-slot:heading>
    @elseif ($title)
        <x-slot:heading class="dashboard-panel-heading">
            <x-slot:title>
                <flux:heading class="flex items-center gap-x-2">
                    @if ($icon)
                        <span @class(["dashboard-panel-icon--{$tone}"])>
                            <flux:icon :name="$icon" class="h-4 w-4 shrink-0" />
                        </span>
                    @endif
                    <span>{{ $title }}</span>
                </flux:heading>
            </x-slot:title>

            @if ($description)
                <x-slot:description class="text-sm text-zinc-500">{{ $description }}</x-slot:description>
            @endif
        </x-slot:heading>
    @endif

    <x-slot:slot class="dashboard-panel-body">
        {{ $slot }}
    </x-slot:slot>

    @if (isset($footer))
        <x-slot:footer>
            {{ $footer }}
        </x-slot:footer>
    @endif
</x-card>
