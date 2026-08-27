<?php

use App\Concerns\ImageValidationRules;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use ImageValidationRules, WithFileUploads;

    #[Locked]
    public Product $product;

    public $image = null;

    public int $imageFieldKey = 0;

    public function updatedImage(): void
    {
        if ($this->image === null) {
            return;
        }

        try {
            $this->validateOnly('image', [
                'image' => $this->imageRules(required: true),
            ]);
        } catch (ValidationException $e) {
            $this->resetImageField();

            throw $e;
        }
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->product);

        try {
            $validated = $this->validate([
                'image' => $this->imageRules(required: true),
            ]);
        } catch (ValidationException $e) {
            $this->resetImageField();

            throw $e;
        }

        $this->product->addMedia($validated['image'])->toMediaCollection('image');

        toast_success('update-image');

        $this->redirect(route('dashboard.products.show', $this->product), navigate: true);
    }

    protected function resetImageField(): void
    {
        $this->reset('image');
        $this->imageFieldKey++;
    }
}; ?>

<div>
    <flux:modal.trigger name="update-image">
        <flux:button type="button" size="sm" icon="photo" iconVariant="outline">
            {{ __('تحديث الصورة') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="update-image" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تحديث الصورة') }}</flux:heading>
                <flux:text class="mt-2">{{ __('اختر الصورة الجديدة للمنتج.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('image')]) for="image" badge="*" required>{{ __('الصورة') }}</flux:label>
                    <flux:input type="file" id="image" wire:model="image" wire:key="product-image-{{ $imageFieldKey }}" class="p-1 border border-zinc-200 rounded-lg" size="sm" accept="image/jpeg,image/png,image/webp" required />
                    <flux:error name="image" />
                </flux:field>
            </div>

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
