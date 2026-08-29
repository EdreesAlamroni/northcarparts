<?php

namespace App\Actions\Product;

use App\Models\Product;

class SyncSpecification
{
    public function execute(Product $product, array $specifications): void
    {
        $attributes = [];

        foreach ($specifications as $id => $value) {
            if (! filled($value)) {
                continue;
            }

            $attributes[(int) $id] = ['value' => trim($value)];
        }

        $product->specifications()->sync($attributes);
    }
}
