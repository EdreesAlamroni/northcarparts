<?php

use App\Models\Specification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public function submit(): void
    {
        Gate::authorize('create', Specification::class);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Specification::class, 'name'),
            ],
        ]);

        $specification = Specification::create([
            'name' => $validated['name'],
        ]);

        toast_success('create');

        $this->redirect(route('dashboard.specifications.index'), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="create">
        <flux:button type="button" variant="primary" size="sm" icon="plus">
            {{ __('إضافة خاصية جديدة') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="create" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('إضافة خاصية جديدة') }}</flux:heading>
                <flux:text class="mt-2">{{ __('أدخل اسم الخاصية الجديدة.') }}</flux:text>
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
