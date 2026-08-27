@props([
    'title' => __('عرض التفاصيل'),
    'href' => '#',
    'icon' => true,
    'disabled' => false,
])

<flux:link
    {{ $attributes->merge([
        'variant' => 'ghost',
        'class' => 'view',
        'href' => $href,
        'disabled' => $disabled,
        'onclick' => ($href === '#' || $disabled) ? 'event.preventDefault();' : '',
        'wire:navigate' => true,
    ]) }}
>
    <span>{{ $title }}</span>
    @if ($icon)
        <flux:icon name="arrow-long-left" variant="solid" class="h-4 w-4 shrink-0" />
    @endif
</flux:link>
