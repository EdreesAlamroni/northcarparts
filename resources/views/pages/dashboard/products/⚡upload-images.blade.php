<?php

use App\Concerns\ImageValidationRules;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use ImageValidationRules, WithFileUploads;

    #[Locked]
    public Product $product;

    public array $images = [];

    public int $imagesFieldKey = 0;

    public function submit(): void
    {
        Gate::authorize('update', $this->product);

        $validated = $this->validate([
            'images' => $this->imagesRules(required: true),
            'images.*' => $this->imageItemRules(),
        ]);

        foreach ($validated['images'] as $image) {
            $this->product->addMedia($image)->toMediaCollection('image');
        }

        toast_success('update-image');

        navigate_preserving_scroll(route('dashboard.products.show', $this->product));
    }
}; ?>

<div>
    <flux:modal.trigger name="upload-images">
        <flux:button type="button" size="sm" icon="arrow-up-tray" iconVariant="outline">
            {{ __('إضافة صور جديدة') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="upload-images" class="md:w-lg w-sm">
        <form wire:submit.preserve-scroll="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('إضافة صور جديدة') }}</flux:heading>
                <flux:text class="mt-2">{{ __('اختر صور المنتج الجديدة.') }}</flux:text>
            </div>

            <flux:field>
                <flux:label @class(['text-red-600' => $errors->has('images') || $errors->has('images.*')]) for="images" badge="*" required>{{ __('الصور') }}</flux:label>
                <x-filepond-livewire
                    id="images"
                    name="images"
                    wire:key="upload-images-{{ $imagesFieldKey }}"
                    :accept="allowedImageMimetypes()->implode(', ')"
                    multiple
                    required
                />
                <flux:error name="images" />
                <flux:error name="images.*" />
            </flux:field>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button type="button" size="sm">{{ __('إلغاء الأمر') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">
                    {{ __('تـأكـيـد') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
