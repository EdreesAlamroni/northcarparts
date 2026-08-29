<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateProduct
{
    public function execute(
        Product $product,
        array $attributes,
        array $specifications,
        array $crossReferences,
    ): Product {
        return DB::transaction(function () use ($product, $attributes, $specifications, $crossReferences) {
            $product->update($attributes);

            app(SyncSpecification::class)->execute($product, $specifications);
            app(SyncCrossReferences::class)->execute($product, $crossReferences);

            return $product->refresh();
        });
    }
}
