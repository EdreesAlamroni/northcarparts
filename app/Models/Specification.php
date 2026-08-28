<?php

namespace App\Models;

use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_specification')
            ->withPivot('value');
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
