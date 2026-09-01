<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\ModelStateUtilities;
use App\ModelStates\Product\ProductState;
use App\ModelStates\Product\States\Visible;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $uuid
 * @property int $category_id
 * @property int|null $oem_manufacturer_id
 * @property string $slug
 * @property string $code
 * @property string|null $oem_number
 * @property int $sort_order
 * @property ProductState $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Manufacturer|null $manufacturer
 * @property-read EloquentCollection<int, Specification> $specifications
 * @property-read EloquentCollection<int, ProductCrossReference> $crossReferences
 * @property-read EloquentCollection<int, Brand> $brands
 */
#[Guarded(['id'])]
class Product extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasStates, HasUuid, InteractsWithMedia, ModelStateUtilities;

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->sort_order)) {
                $product->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'state' => ProductState::class,
        ];
    }

    /*
     * Start: Scopes
     */

    #[Scope]
    protected function visible(Builder $query): Builder
    {
        return $query->whereState('state', Visible::class);
    }

    /*
     * End: Scopes
     */

    /*
     * Start: Relations
     */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'oem_manufacturer_id');
    }

    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class, 'product_specification')
            ->withPivot('value');
    }

    public function crossReferences(): HasMany
    {
        return $this->hasMany(ProductCrossReference::class);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'product_cross_references')
            ->withPivot(['reference_code', 'reference_code_normalized']);
    }

    /*
     * End: Relations
     */

    /*
     * Start: Custom Functions
     */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /*
     * End: Custom Functions
     */
}
