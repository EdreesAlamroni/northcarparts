@props([
    'beforeLabel' => __('الحقول التي تحتوي على علامة'),
    'afterLabel' => __('مطلوبة'),
])

<div {{ $attributes->merge([
    'role' => 'note',
    'aria-live' => 'polite'
]) }}>
    <span>{{ $beforeLabel }}</span>
    <span class="text-red-500">*</span>
    <span>{{ $afterLabel }}</span>
</div>
