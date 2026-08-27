<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Title('إعدادات الحساب'), Layout('layouts::dashboard')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::guard('web')->user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('تم تحديث كلمة المرور بنجاح.'));
    }


}; ?>

@php
    $breadcrumbs = [
        ['name' => __('إعدادات الحساب'), 'active' => true],
    ];
@endphp

<x-slot:breadcrumbs>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
</x-slot:breadcrumbs>

<x-slot:mobile-breadcrumbs>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
</x-slot:mobile-breadcrumbs>

<section class="w-full">
    @include('partials.account-settings-heading')

    <flux:heading class="sr-only">{{ __('الأمان') }}</flux:heading>

    <x-pages::dashboard.account-settings.layout :heading="__('تحديث كلمة المرور')" :subheading="__('تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للبقاء آمنا')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('كلمة المرور الحالية')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('كلمة المرور الجديدة')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('تأكيد كلمة المرور')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-password-button">
                    {{ __('حفظ') }}
                </flux:button>
            </div>
        </form>


    </x-pages::dashboard.account-settings.layout>

</section>
