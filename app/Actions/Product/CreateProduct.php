<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateProduct
{
    public function execute(
        array $attributes,
        array $specifications,
        array $crossReferences,
        ?UploadedFile $image = null,
    ): Product {
        return DB::transaction(function () use ($attributes, $specifications, $crossReferences, $image) {
            /** @var Product $product */
            $product = Product::create($attributes);

            if ($image !== null) {
                $product->addMedia($image)->toMediaCollection('image');
            }

            app(SyncSpecification::class)->execute($product, $specifications);
            app(SyncCrossReferences::class)->execute($product, $crossReferences);

            return $product;
        });
    }
}
