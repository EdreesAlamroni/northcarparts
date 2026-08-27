@props([
    'value' => '',
])

@php
    $isFilled = filled($value);
@endphp

<a
    {{
        $attributes
            ->class([
                'focus:!ring-0',
                'hover:underline' => $isFilled,
                'cursor-default font-mono' => ! $isFilled,
            ])
            ->merge($isFilled ? [
                'href' => "mailto:{$value}",
                'title' => __('إضغط للمراسلة'),
            ] : [
                'href' => '#',
                'aria-disabled' => 'true',
                'tabindex' => '-1',
                'onclick' => 'event.preventDefault();',
            ])
    }}
>
    {{ $isFilled ? $value : '-' }}
</a>
