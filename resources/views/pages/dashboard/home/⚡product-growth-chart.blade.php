<?php

use App\Queries\Dashboard\ProductGrowthTrend;
use Livewire\Attributes\Defer;
use Livewire\Component;

new #[Defer(bundle: true)] class extends Component
{
    public array $chartData = [
        'type' => 'line',
        'labels' => [],
        'data' => [],
        'label' => '',
    ];

    public function mount(ProductGrowthTrend $productGrowthTrend): void
    {
        $trend = $productGrowthTrend();

        $this->chartData = [
            'type' => 'line',
            'labels' => $trend->map(fn (array $point): string => $point['date'])->all(),
            'data' => $trend->map(fn (array $point): int => $point['count'])->all(),
            'label' => __('المنتجات المضافة'),
        ];
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('pages::dashboard.home.partials.chart-skeleton', ['tone' => 'blue']);
    }
}; ?>

<x-dashboard.panel-card
    tone="blue"
    icon="chart-bar"
    :title="__('نمو المنتجات')"
    :description="__('عدد المنتجات المضافة خلال آخر 30 يومًا')"
>
    <div wire:ignore class="h-64" x-data="dashboardChart" data-chart-config='@json($chartData)'>
        <canvas x-ref="canvas"></canvas>
    </div>
</x-dashboard.panel-card>
