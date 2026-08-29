<?php

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Brand $brand;

    public string $name = '';

    public function mount(): void
    {
        $this->name = $this->brand->name;
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->brand);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Brand::class, 'name')->ignore($this->brand),
            ],
        ]);

        $this->brand->update([
            'name' => $validated['name'],
        ]);

        toast_success('update');

        $this->redirect(url()->previous() ?? route('dashboard.brands.index'), navigate: true);
    }
}; ?>

@php($modal = 'update-brand-'.$brand->id)

<div>
    <flux:modal.trigger name="{{ $modal }}">
        <flux:button type="button" size="sm" icon="pencil-square" iconVariant="outline" />
    </flux:modal.trigger>

    <flux:modal name="{{ $modal }}" class="md:w-lg w-sm text-start">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تعديل بيانات العلامة التجارية') }}</flux:heading>
                <flux:text class="mt-2">{{ __('قم بتعديل اسم العلامة التجارية.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('name')]) for="name-{{ $brand->id }}" badge="*" required>{{ __('الاسم') }}</flux:label>
                    <flux:input type="text" id="name-{{ $brand->id }}" wire:model="name" autocomplete="off" required />
                    <flux:error name="name" />
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
