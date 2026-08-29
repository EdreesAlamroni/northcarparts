<?php

use App\Models\Specification;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Specification $specification;

    public function delete(): void
    {
        Gate::authorize('delete', $this->specification);

        $this->specification->delete();

        toast_success('delete');

        $this->redirect(route('dashboard.specifications.index', request()->query()), navigate: true);
    }
}; ?>

@php($modal = 'delete-specification-'.$specification->id)

<div>
    <flux:modal.trigger name="{{ $modal }}">
        <flux:button type="button" variant="danger" size="sm" icon="trash" iconVariant="outline" class="[&:focus]:!ring-red-500" />
    </flux:modal.trigger>

    <flux:modal name="{{ $modal }}" class="sm:min-w-lg min-w-sm text-start">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('هل أنت متأكد من الحذف ؟') }}</flux:heading>
                <flux:text class="mt-2 text-sm">{{ __('لن تتمكن من التراجع عن هذا اﻹجراء .') }}</flux:text>
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
                    wire:click="delete"
                >
                    {{ __('تـأكـيـد') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
