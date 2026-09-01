<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateProduct
{
    /**
     * @param  array<int, UploadedFile>  $images
     */
    public function execute(
        array $attributes,
        array $specifications,
        array $crossReferences,
        array $images = [],
    ): Product {
        return DB::transaction(function () use ($attributes, $specifications, $crossReferences, $images) {
            /** @var Product $product */
            $product = Product::create($attributes);

            foreach ($images as $image) {
                $product->addMedia($image)->toMediaCollection('image');
            }

            app(SyncSpecification::class)->execute($product, $specifications);
            app(SyncCrossReferences::class)->execute($product, $crossReferences);

            return $product;
        });
    }
}
