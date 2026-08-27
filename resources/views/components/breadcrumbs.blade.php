@props(['breadcrumbs' => []])

@if (count($breadcrumbs) > 0)
    <nav aria-label="Breadcrumb" class="flex">
        <ol role="list" class="flex items-center flex-wrap gap-3">
            @foreach ($breadcrumbs as $breadcrumb)
                <li>
                    @if ($loop->first)
                        <div>
                            @if(isset($breadcrumb['active']) && $breadcrumb['active'])
                                <span class="text-sm font-medium text-zinc-500">{{ $breadcrumb['name'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="text-sm font-medium text-zinc-500 hover:text-zinc-700" wire:navigate>{{ $breadcrumb['name'] }}</a>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center">
                            <flux:icon name="chevron-left" class="size-5 shrink-0 text-zinc-400" />

                            @if(isset($breadcrumb['active']) && $breadcrumb['active'])
                                <span class="ms-3 text-sm font-medium text-zinc-500">{{ $breadcrumb['name'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="ms-3 text-sm font-medium text-zinc-500 hover:text-zinc-700" wire:navigate>{{ $breadcrumb['name'] }}</a>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

