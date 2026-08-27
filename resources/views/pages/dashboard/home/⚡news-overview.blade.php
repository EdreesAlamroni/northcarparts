<?php

use App\Queries\Dashboard\NewsOverviewStats;
use Livewire\Attributes\Defer;
use Livewire\Component;

new #[Defer(bundle: true)] class extends Component
{
    public array $stats = [
        'total' => 0,
        'visible' => 0,
    ];

    public function mount(NewsOverviewStats $newsOverviewStats): void
    {
        $this->stats = $newsOverviewStats();
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('pages::dashboard.home.partials.news-skeleton');
    }
}; ?>

<div class="grid auto-rows-min gap-4 sm:gap-5 md:grid-cols-2">
    <x-dashboard.stat-card
        icon="newspaper"
        tone="sky"
        :label="__('إجمالي الأخبار')"
        :value="$stats['total']"
    />

    <x-dashboard.stat-card
        icon="check-circle"
        tone="green"
        :label="__('الأخبار المعروضة')"
        :value="$stats['visible']"
    />
</div>
