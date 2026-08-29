<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property EloquentCollection<int, ProductCrossReference> $crossReferences
 * @property EloquentCollection<int, Product> $products
 */
#[Guarded(['id'])]
class Brand extends Model
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory;

    /*
     * Start: Relations
     */

    public function crossReferences(): HasMany
    {
        return $this->hasMany(ProductCrossReference::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_cross_references')
            ->withPivot(['reference_code', 'reference_code_normalized']);
    }

    /*
     * End: Relations
     */

    /*
     * Start: Custom Functions
     */

    public static function list(?callable $callback = null, array $additionalColumns = ['id', 'name']): Collection
    {
        $columns = array_unique(
            array_merge(['id', 'name'], $additionalColumns)
        );

        $query = self::query()->select($columns);

        if ($callback) {
            $callback($query);
        }

        return $query
            ->orderBy('name')
            ->pluck('name', 'id')
            ->map(function (string $name, int $id): object {
                return (object) [
                    'id' => $id,
                    'name' => $name,
                ];
            })->values();
    }

    /*
     * End: Custom Functions
     */
}
