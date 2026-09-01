<?php

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new class extends Component
{
    #[Locked]
    public Product $product;

    #[Locked]
    public Media $media;

    public function delete(): void
    {
        Gate::authorize('update', $this->product);

        abort_unless(
            $this->product->getMedia('image')->contains('id', $this->media->id),
            404,
        );

        $this->media->delete();

        toast_success('media-delete');

        navigate_preserving_scroll(route('dashboard.products.show', $this->product));
    }
}; ?>

@php($modal = 'delete-image-'.$media->id)

<div>
    <flux:modal.trigger name="{{ $modal }}">
        <flux:button type="button" variant="danger" size="sm" icon="trash" class="[&:focus]:!ring-red-500" />
    </flux:modal.trigger>

    <flux:modal name="{{ $modal }}" class="sm:min-w-lg min-w-sm text-start">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('هل أنت متأكد من الحذف ؟') }}</flux:heading>
                <flux:text class="mt-2 text-sm">
                    {{ __('سيتم حذف هذه الصورة. لن تتمكن من التراجع عن هذا الإجراء.') }}
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button type="button" size="sm">{{ __('إلغاء الأمر') }}</flux:button>
                </flux:modal.close>

                <flux:button
                    type="button"
                    variant="danger"
                    size="sm"
                    icon="check-circle"
                    iconVariant="outline"
                    class="[&:focus]:!ring-red-500"
                    wire:click.preserve-scroll="delete"
                >
                    {{ __('تـأكـيـد') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
