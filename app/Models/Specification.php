<?php

namespace App\Models;

use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EloquentCollection<int, SpecificationValue> $values
 * @property-read EloquentCollection<int, Product> $products
 */
#[Guarded(['id'])]
class Specification extends Model
{
    /** @use HasFactory<\Database\Factories\SpecificationFactory> */
    use HasFactory, HasUuid;

    /*
     * Start: Relations
     */

    public function values(): HasMany
    {
        return $this->hasMany(SpecificationValue::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_specification_value',
            'specification_value_id',
            'product_id'
        )->whereIn('product_specification_value.specification_value_id', function ($query): void {
            $query
                ->select('id')
                ->from('specification_values')
                ->whereColumn('specification_values.specification_id', 'specifications.id');
        });
    }

    /*
     * End: Relations
     */
}
