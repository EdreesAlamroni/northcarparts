<?php

use App\Queries\Dashboard\CatalogOverviewStats;
use Livewire\Component;

new class extends Component
{
    public array $stats = [
        'categories' => 0,
        'manufacturers' => 0,
    ];

    public function mount(CatalogOverviewStats $catalogOverviewStats): void
    {
        $this->stats = $catalogOverviewStats();
    }
}; ?>

<div class="grid auto-rows-min gap-4 sm:gap-5 md:grid-cols-2">
    <x-dashboard.stat-card
        icon="squares-2x2"
        tone="teal"
        :label="__('التصنيفات')"
        :value="$stats['categories']"
    />

    <x-dashboard.stat-card
        icon="building-office"
        tone="orange"
        :label="__('الشركات المصنعة')"
        :value="$stats['manufacturers']"
    />
</div>
