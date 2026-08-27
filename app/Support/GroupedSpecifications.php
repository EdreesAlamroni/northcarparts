<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Specification;
use Illuminate\Support\Collection;

class GroupedSpecifications
{
    public function all(): Collection
    {
        return Specification::query()
            ->with(['values' => function ($query): void {
                $query->oldest('value');
            }])
            ->oldest('name')
            ->get()
            ->map(function (Specification $specification): object {
                return (object) [
                    'key' => (string) $specification->id,
                    'label' => $specification->name,
                    'values' => $specification->values->map(function ($value): object {
                        return (object) [
                            'id' => $value->id,
                            'label' => $value->value,
                        ];
                    })->values(),
                ];
            })
            ->values();
    }

    public function forProduct(Product $product): Collection
    {
        if (! $product->relationLoaded('specificationValues')) {
            $product->load(['specificationValues.specification:id,name']);
        } else {
            $product->specificationValues->loadMissing('specification:id,name');
        }

        if ($product->specificationValues->isEmpty()) {
            return collect([]);
        }

        return $product->specificationValues
            ->groupBy('specification_id')
            ->map(function (Collection $values): object {
                $specification = $values->first()->specification;

                return (object) [
                    'key' => (string) $specification->id,
                    'label' => $specification->name,
                    'values' => $values->map(function ($value): object {
                        return (object) [
                            'id' => $value->id,
                            'label' => $value->value,
                        ];
                    })->values(),
                ];
            })
            ->values();
    }
}
