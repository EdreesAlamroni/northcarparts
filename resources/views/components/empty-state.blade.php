@props([
    'hasFilter' => null,
    'text' => __('عذرًا، لا توجد بيانات للعرض حاليًا.'),
    'textFilter' => __('عذرًا، لا توجد نتائج مطابقة للبحث.'),
    'description' => null,
    'icon' => null,
])

@php
    $hasFilter ??= request()->has('filter');
    $message = $hasFilter ? $textFilter : $text;
    $iconName = $icon ?? ($hasFilter ? 'search-x' : 'inbox');
@endphp

<div
    role="status"
    {{ $attributes->class([
        'relative isolate flex flex-col items-center gap-3 py-6 text-center',
    ]) }}
>
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,var(--color-zinc-200)_1px,transparent_0)] bg-size-[16px_16px] opacity-35"
        aria-hidden="true"
    ></div>

    <div class="relative flex flex-col items-center gap-2">
        <div @class([
            'inline-flex size-9 items-center justify-center bg-white shadow-sm ring-1',
            'text-zinc-500 ring-zinc-200/70' => $hasFilter,
            'text-accent ring-accent/15' => ! $hasFilter,
        ]) aria-hidden="true">
            <flux:icon
                :name="$iconName"
                variant="outline"
                class="size-[18px] shrink-0 stroke-[1.5]"
            />
        </div>

        <span @class([
            'h-px w-7',
            'bg-zinc-200/80' => $hasFilter,
            'bg-accent/30' => ! $hasFilter,
        ]) aria-hidden="true"></span>
    </div>

    <div class="relative max-w-sm space-y-1 px-2">
        <p class="text-sm font-medium text-balance text-zinc-900">
            {{ $message }}
        </p>

        @if ($description)
            <p class="text-xs leading-relaxed text-balance text-zinc-500">
                {{ $description }}
            </p>
        @endif
    </div>
</div>
