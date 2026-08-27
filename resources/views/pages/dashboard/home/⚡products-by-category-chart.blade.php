<?php

use App\Queries\Dashboard\ProductsByCategoryDistribution;
use Livewire\Attributes\Defer;
use Livewire\Component;

new #[Defer(bundle: true)] class extends Component
{
    public array $chartData = [
        'type' => 'bar',
        'orientation' => 'vertical',
        'rtl' => true,
        'labels' => [],
        'data' => [],
        'label' => '',
        'colors' => [],
    ];

    private array $palette = [
        'rgb(59, 130, 246)',
    ];

    public function mount(ProductsByCategoryDistribution $productsByCategoryDistribution): void
    {
        $distribution = $productsByCategoryDistribution();
        $labels = $distribution->pluck('name')->all();
        $data = $distribution->pluck('count')->all();

        $this->chartData = [
            'type' => 'bar',
            'orientation' => 'vertical',
            'rtl' => true,
            'labels' => $labels,
            'data' => $data,
            'label' => __('عدد المنتجات'),
            'colors' => collect($data)
                ->keys()
                ->map(fn (int $index): string => $this->palette[$index % count($this->palette)])
                ->all(),
        ];
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('pages::dashboard.home.partials.chart-skeleton', ['tone' => 'teal']);
    }
}; ?>

<x-dashboard.panel-card
    tone="teal"
    icon="squares-2x2"
    :title="__('المنتجات حسب التصنيف')"
>
    @if (count($chartData['labels']) === 0)
        <x-empty-state :text="__('لا توجد تصنيفات بعد.')" />
    @else
        <div wire:ignore class="h-72 font-sans" dir="rtl" x-data="dashboardChart" data-chart-config='@json($chartData)'>
            <canvas x-ref="canvas"></canvas>
        </div>
    @endif
</x-dashboard.panel-card>
