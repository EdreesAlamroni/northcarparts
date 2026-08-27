<?php

use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Specification $specification;

    #[Locked]
    public SpecificationValue $value;

    public string $valueText = '';

    public function mount(): void
    {
        $this->valueText = $this->value->value;
    }

    public function submit(): void
    {
        Gate::authorize('update', $this->specification);

        if ($this->value->specification_id !== $this->specification->id) {
            abort(403);
        }

        $validated = $this->validate([
            'valueText' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specification_values', 'value')
                    ->where('specification_id', $this->specification->id)
                    ->ignore($this->value->id),
            ],
        ]);

        $this->value->update([
            'value' => $validated['valueText'],
        ]);

        toast_success('update');

        $this->redirect(route('dashboard.specifications.show', $this->specification), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="update-value-{{ $value->id }}">
        <flux:button type="button" size="sm" icon="pencil-square" iconVariant="outline" />
    </flux:modal.trigger>

    <flux:modal name="update-value-{{ $value->id }}" class="md:w-lg w-sm text-start">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تعديل قيمة الخاصية') }}</flux:heading>
                <flux:text class="mt-2">{{ __('قم بتعديل قيمة الخاصية.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('valueText')]) for="valueText-{{ $value->id }}" badge="*" required>{{ __('قيمة الخاصية') }}</flux:label>
                    <flux:input type="text" id="valueText-{{ $value->id }}" wire:model="valueText" autocomplete="off" required />
                    <flux:error name="valueText" />
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
