<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\ModelStateUtilities;
use App\ModelStates\Category\CategoryState;
use App\ModelStates\Category\States\Visible;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property string|null $technical_description
 * @property int $sort_order
 * @property CategoryState $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $summary
 */
#[Guarded(['id'])]
class Category extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, HasStates, HasUuid, InteractsWithMedia, ModelStateUtilities;

    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (blank($category->sort_order)) {
                $category->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'state' => CategoryState::class,
        ];
    }

    /*
     * Start: Accessors & Mutators
     */

    protected function summary(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (blank($this->description)) {
                return null;
            }

            return Str::limit(strip_tags($this->description), 160);
        });
    }

    /*
     * End: Accessors & Mutators
     */

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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
    }

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
            ->orderBy('sort_order')
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
