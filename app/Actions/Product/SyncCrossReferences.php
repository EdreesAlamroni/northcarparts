<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Support\PartNumberNormalizer;

class SyncCrossReferences
{
    public function execute(Product $product, array $crossReferences): void
    {
        $attributes = [];

        foreach ($crossReferences as $brandId => $referenceCode) {
            if (blank($referenceCode)) {
                continue;
            }

            $attributes[(int) $brandId] = [
                'reference_code' => $referenceCode,
                'reference_code_normalized' => PartNumberNormalizer::normalize($referenceCode),
            ];
        }

        $product->brands()->sync($attributes);
    }
}
