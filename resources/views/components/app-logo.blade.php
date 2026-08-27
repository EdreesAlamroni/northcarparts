@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="نظام قطع الغيار" {{ $attributes }}>
        <x-slot name="logo" class="flex size-10 items-center justify-center border rounded-full bg-accent-content">
            <x-app-logo-icon class="size-full fill-current" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }}>
        <x-slot name="logo" class="flex size-10 items-center justify-center border rounded-full bg-accent-content">
            <x-app-logo-icon class="size-full fill-current" />
        </x-slot>
    </flux:brand>
@endif
