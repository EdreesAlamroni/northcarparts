@props([
    'value' => '',
])

@php
    $isFilled = filled($value);
    $url = $isFilled
        ? (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') ? $value : "https://{$value}")
        : '#';
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
                'href' => $url,
                'title' => __('إضغط لزيارة الرابط'),
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
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
