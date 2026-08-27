<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\ModelStateUtilities;
use App\ModelStates\News\NewsState;
use App\ModelStates\News\States\Visible;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

/**
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property NewsState $state
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $excerpt
 */
#[Guarded(['id'])]
class News extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory, HasStates, HasUuid, InteractsWithMedia, ModelStateUtilities;

    protected function casts(): array
    {
        return [
            'state' => NewsState::class,
            'published_at' => 'date',
        ];
    }

    /*
     * Start: Accessors & Mutators
     */

    protected function excerpt(): Attribute
    {
        return Attribute::get(function (): string {
            return Str::limit(strip_tags($this->content), 160);
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
     * Start: Custom Functions
     */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /*
     * End: Custom Functions
     */
}
