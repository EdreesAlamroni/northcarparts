<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\ModelStateUtilities;
use App\Enums\FilterType;
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
 * @property string|null $name
 * @property FilterType $filter_type
 * @property string|null $oem_number
 * @property string $qr_code_redirect_url
 * @property int $sort_order
 * @property ProductState $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Manufacturer|null $manufacturer
 * @property-read EloquentCollection<int, SpecificationValue> $specificationValues
 */
#[Guarded(['id'])]
class Product extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasStates, HasUuid, InteractsWithMedia, ModelStateUtilities;

    protected function casts(): array
    {
        return [
            'filter_type' => FilterType::class,
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

    public function specificationValues(): BelongsToMany
    {
        return $this->belongsToMany(SpecificationValue::class, 'product_specification_value');
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
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('qr_code')
            ->singleFile()
            ->acceptsMimeTypes(['image/png']);
    }

    public function redirectUrl(): string
    {
        return $this->qr_code_redirect_url;
    }

    /*
     * End: Custom Functions
     */
}
