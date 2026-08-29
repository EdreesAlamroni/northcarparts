<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $brand_id
 * @property string $reference_code
 * @property string $reference_code_normalized
 * @property-read Product $product
 * @property-read Brand $brand
 */
#[Guarded(['id'])]
class ProductCrossReference extends Model
{
    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'brand_id' => 'integer',
        ];
    }

    /*
     * Start: Relations
     */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /*
     * End: Relations
     */
}
