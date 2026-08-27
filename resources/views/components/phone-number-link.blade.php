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
                '!font-normal font-mono',
                'font-mono hover:underline' => $isFilled,
                'cursor-default' => ! $isFilled,
            ])
            ->merge($isFilled ? [
                'href' => "tel:{$value}",
                'title' => __('إضغط للإتصال'),
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
