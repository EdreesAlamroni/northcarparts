<?php

use App\Models\Manufacturer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Manufacturer $manufacturer;

    public string $name = '';

    public function mount(): void
    {
        $this->name = $this->manufacturer->name;
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->manufacturer);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Manufacturer::class, 'name')->ignore($this->manufacturer),
            ],
        ]);

        $this->manufacturer->update([
            'name' => $validated['name'],
        ]);

        toast_success('update');

        $this->redirect(url()->previous() ?? route('dashboard.manufacturers.index'), navigate: true);
    }
}; ?>

@php($modal = 'update-manufacturer-'.$manufacturer->id)

<div>
    <flux:modal.trigger name="{{ $modal }}">
        <flux:button type="button" size="sm" icon="pencil-square" iconVariant="outline" />
    </flux:modal.trigger>

    <flux:modal name="{{ $modal }}" class="md:w-lg w-sm text-start">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تعديل بيانات الشركة المصنعة') }}</flux:heading>
                <flux:text class="mt-2">{{ __('قم بتعديل اسم الشركة المصنعة.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('name')]) for="name-{{ $manufacturer->id }}" badge="*" required>{{ __('الاسم') }}</flux:label>
                    <flux:input type="text" id="name-{{ $manufacturer->id }}" wire:model="name" autocomplete="off" required />
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
