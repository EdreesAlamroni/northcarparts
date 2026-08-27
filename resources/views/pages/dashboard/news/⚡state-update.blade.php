<?php

use App\Models\News;
use App\ModelStates\News\NewsState;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\ModelStates\Validation\ValidStateRule;

new class extends Component
{
    #[Locked]
    public News $news;

    public string $state = '';

    public function submit(): void
    {
        Gate::authorize('update', $this->news);

        $validated = $this->validate([
            'state' => [
                'required',
                new ValidStateRule(NewsState::class),
            ],
        ]);

        $this->news = $this->news->state->transitionTo(NewsState::resolve($validated['state']));

        toast_success('state-update');

        $this->redirect(route('dashboard.news.show', $this->news), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="state-update">
        <flux:button type="button" size="sm" icon="arrow-path" iconVariant="outline">
            {{ __('تحديث الحالة') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="state-update" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تحديث الحالة') }}</flux:heading>
                <flux:text class="mt-2">{{ __('اختر الحالة الجديدة للخبر.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('state')]) for="state" badge="*" required>{{ __('الحالة') }}</flux:label>
                    <flux:select id="state" wire:model="state" :placeholder="__('اختر الحالة')" required>
                        @foreach ($this->news->getTransitionableStates() as $option)
                            <flux:select.option :value="$option->id">
                                {{ $option->action ?? $option->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="state" />
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
