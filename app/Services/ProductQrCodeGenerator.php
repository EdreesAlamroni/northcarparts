<?php

namespace App\Services;

use App\Models\Product;
use Endroid\QrCode\Builder\Builder;

class ProductQrCodeGenerator
{
    public function generate(Product $product): void
    {
        $result = new Builder(
            data: $product->redirectUrl(),
            size: 300,
            margin: 10,
        )->build();

        $product->addMediaFromString($result->getString())
            ->usingFileName(sprintf('%s-qr.png', $product->code))
            ->toMediaCollection('qr_code');
    }

    public function regenerate(Product $product): void
    {
        $product->clearMediaCollection('qr_code');

        $this->generate($product);
    }
}
