<div {{ $attributes->merge(['class' => 'border border-zinc-200 divide-y divide-zinc-200 rounded-lg bg-white shadow-md']) }}>
    @isset($heading)
        <div {{ $heading->attributes->merge(['class' => 'p-4']) }}>
            @if (isset($title) || isset($actions))
                <div class="flex flex-wrap items-center justify-between gap-3">
                    @isset($title)
                        <div {{ $title->attributes->merge(['class' => 'min-w-0 flex items-center gap-x-2 text-sm font-medium']) }}>
                            {{ $title }}
                        </div>
                    @endisset

                    @isset($actions)
                        <div {{ $actions->attributes->merge(['class' => 'shrink-0']) }}>
                            {{ $actions }}
                        </div>
                    @endisset
                </div>
            @else
                {{ $heading }}
            @endif

            @isset($description)
                <div {{ $description->attributes->merge(['class' => 'mt-2.5 text-xs font-normal text-zinc-500']) }}>
                    {{ $description }}
                </div>
            @endisset
        </div>
    @endisset

    <div {{ $slot->attributes->merge(['class' => 'p-4']) }}>
        {{ $slot }}
    </div>

    @isset($footer)
        <div {{ $footer->attributes->merge(['class' => 'p-4']) }}>
            {{ $footer }}
        </div>
    @endisset
</div>
