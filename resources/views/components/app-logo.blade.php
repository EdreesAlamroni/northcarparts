@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}>
        <x-slot name="logo">
            <x-app-logo-icon class="size-full" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }}>
        <x-slot name="logo">
            <x-app-logo-icon class="size-full" />
        </x-slot>
    </flux:brand>
@endif
