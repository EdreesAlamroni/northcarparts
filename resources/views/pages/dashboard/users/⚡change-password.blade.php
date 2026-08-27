<?php

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    use PasswordValidationRules;

    #[Locked]
    public User $user;

    public string $password = '';

    public string $password_confirmation = '';

    public function submit(): void
    {
        Gate::authorize('update', $this->user);

        $validated = $this->validate([
            'password' => $this->passwordRules(),
        ]);

        $this->user->update([
            'password' => $validated['password'],
        ]);

        toast_success('update-password');

        $this->redirect(route('dashboard.users.show', $this->user), navigate: true);
    }
}; ?>

<div>
    <flux:modal.trigger name="change-password">
        <flux:button type="button" size="sm" icon="lock-closed" iconVariant="outline">
            {{ __('تغيير كلمة المرور') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="change-password" class="md:w-lg w-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <flux:heading size="base">{{ __('تغيير كلمة المرور') }}</flux:heading>
                <flux:text class="mt-2">{{ __('أدخل كلمة المرور الجديدة لتحديث كلمة المرور.') }}</flux:text>
            </div>

            <div class="space-y-6">
                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('password')]) for="password" badge="*" required>{{ __('كلمة المرور') }}</flux:label>
                    <flux:input type="password" id="password" wire:model="password" autocomplete="new-password" required viewable />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label @class(['text-red-600' => $errors->has('password_confirmation')]) for="password_confirmation" badge="*" required>{{ __('تأكيد كلمة المرور') }}</flux:label>
                    <flux:input type="password" id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password" required viewable />
                    <flux:error name="password_confirmation" />
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
