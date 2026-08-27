<?php

use App\Queries\Dashboard\TopManufacturersByProductCount;
use Illuminate\Support\Collection;
use Livewire\Attributes\Defer;
use Livewire\Component;

new #[Defer(bundle: true)] class extends Component
{
    public Collection $manufacturers;

    public function mount(TopManufacturersByProductCount $topManufacturersByProductCount): void
    {
        $this->manufacturers = $topManufacturersByProductCount();
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('pages::dashboard.home.partials.stat-skeleton', ['tone' => 'orange']);
    }
}; ?>

<div>
    @if ($manufacturers->isNotEmpty())
        @php
            $maxCount = $manufacturers->max('count') ?: 1;
        @endphp

        <x-dashboard.panel-card tone="orange">
            <ul class="divide-y divide-zinc-100">
                @foreach ($manufacturers as $index => $manufacturer)
                    @php
                        $rank = $index + 1;
                        $barWidth = round(($manufacturer['count'] / $maxCount) * 100);
                    @endphp

                    <li class="relative">
                        <div
                            @class(["absolute inset-y-0 start-0 rounded-md opacity-15"])
                            style="width: {{ $barWidth }}%"
                            aria-hidden="true"
                        ></div>

                        <div class="relative flex items-center gap-4 py-3 px-1">
                            <span class="w-5 shrink-0 text-center text-xs font-mono text-zinc-400">{{ $rank }}</span>

                            <span class="min-w-0 flex-1 truncate text-sm font-medium text-zinc-900">{{ $manufacturer['name'] }}</span>

                            <span @class([
                                'inline-flex shrink-0 items-center rounded-full px-2.5 py-0.5 text-xs font-mono font-medium',
                                "dashboard-rank-badge--{$rank}",
                            ])>
                                {{ number_format($manufacturer['count']) }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-dashboard.panel-card>
    @endif
</div>
