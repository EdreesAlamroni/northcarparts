<?php

namespace App\Queries\Dashboard;

use App\Models\Product;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProductGrowthTrend
{
    public function __invoke(int $days = 30): Collection
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $counts = Product::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        return collect(CarbonPeriod::create($startDate, '1 day', Carbon::now()->startOfDay()))
            ->map(fn (Carbon $date): array => [
                'date' => $date->toDateString(),
                'count' => (int) ($counts[$date->toDateString()] ?? 0),
            ])
            ->values();
    }
}
