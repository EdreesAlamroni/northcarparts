<?php

use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Specification $specification;

    #[Locked]
    public SpecificationValue $value;

    public function delete(): void
    {
        Gate::authorize('update', $this->specification);

        if ($this->value->specification_id !== $this->specification->id) {
            abort(403);
        }

        $this->value->delete();

        toast_success('delete');

        $this->redirect(route('dashboard.specifications.show', $this->specification), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="delete-value-{{ $value->id }}">
        <flux:button type="button" variant="danger" size="sm" icon="trash" />
    </flux:modal.trigger>

    <flux:modal name="delete-value-{{ $value->id }}" class="md:w-lg w-sm text-start">
        <div class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('حذف قيمة الخاصية') }}</flux:heading>
                <flux:text class="mt-2">{{ __('هل أنت متأكد من حذف قيمة الخاصية؟ لا يمكن التراجع عن هذا الإجراء.') }}</flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button type="button" size="sm">{{ __('إلغاء الأمر') }}</flux:button>
                </flux:modal.close>

                <flux:button type="button" variant="danger" size="sm" icon="check-circle" wire:click="delete">
                    {{ __('تـأكـيـد') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
