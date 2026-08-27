<?php

use App\Queries\Dashboard\ProductOverviewStats;
use Livewire\Component;

new class extends Component
{
    public array $stats = [
        'total' => 0,
        'visible' => 0,
        'added_this_month' => 0,
    ];

    public function mount(ProductOverviewStats $productOverviewStats): void
    {
        $this->stats = $productOverviewStats();
    }
}; ?>

<div class="grid auto-rows-min gap-4 sm:gap-5 md:grid-cols-3">
    <x-dashboard.stat-card
        icon="cube"
        tone="blue"
        :label="__('إجمالي المنتجات')"
        :value="$stats['total']"
    />

    <x-dashboard.stat-card
        icon="check-circle"
        tone="green"
        :label="__('المنتجات المعروضة')"
        :value="$stats['visible']"
    />

    <x-dashboard.stat-card
        icon="plus-circle"
        tone="purple"
        :label="__('أُضيفت هذا الشهر')"
        :value="$stats['added_this_month']"
    />
</div>
