<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $specification_id
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Specification $specification
 * @property-read EloquentCollection<int, Product> $products
 */
#[Guarded(['id'])]
class SpecificationValue extends Model
{
    /** @use HasFactory<\Database\Factories\SpecificationValueFactory> */
    use HasFactory;

    /*
     * Start: Relations
     */

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_specification_value');
    }

    /*
     * End: Relations
     */
}
