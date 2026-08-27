<?php

use App\Models\Specification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Specification $specification;

    public string $name = '';

    public function mount(): void
    {
        $this->name = $this->specification->name;
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->specification);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Specification::class, 'name')->ignore($this->specification),
            ],
        ]);

        $this->specification->update([
            'name' => $validated['name'],
        ]);

        toast_success('update');

        $this->redirect(route('dashboard.specifications.show', $this->specification), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="update">
        <flux:button type="button" size="sm" icon="pencil-square" iconVariant="outline">
            {{ __('تعديل بيانات الخاصية') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="update" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تعديل بيانات الخاصية') }}</flux:heading>
                <flux:text class="mt-2">{{ __('قم بتعديل اسم الخاصية.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('name')]) for="name" badge="*" required>{{ __('اسم الخاصية') }}</flux:label>
                    <flux:input type="text" id="name" wire:model="name" autocomplete="off" required />
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
