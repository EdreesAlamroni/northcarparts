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

    public string $value = '';

    public function submit(): void
    {
        Gate::authorize('update', $this->specification);

        $validated = $this->validate([
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specification_values', 'value')
                    ->where('specification_id', $this->specification->id),
            ],
        ]);

        $this->specification->values()->create([
            'value' => $validated['value'],
        ]);

        toast_success('create');

        $this->redirect(route('dashboard.specifications.show', $this->specification), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="create-value">
        <flux:button type="button" variant="outline" size="sm" icon="plus">
            {{ __('إضافة قيمة الخاصية') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-value" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('إضافة قيمة الخاصية') }}</flux:heading>
                <flux:text class="mt-2">{{ __('أدخل قيمة خاصية جديدة لهذه الخاصية.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('value')]) for="value" badge="*" required>{{ __('قيمة الخاصية') }}</flux:label>
                    <flux:input type="text" id="value" wire:model="value" autocomplete="off" required />
                    <flux:error name="value" />
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
