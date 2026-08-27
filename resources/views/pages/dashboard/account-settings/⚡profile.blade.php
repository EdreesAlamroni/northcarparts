<?php

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Title('إعدادات الحساب'), Layout('layouts::dashboard')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::guard('web')->user()->name;
        $this->email = Auth::guard('web')->user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::guard('web')->user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('تم تحديث البيانات الشخصية بنجاح.'));
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

    <flux:heading class="sr-only">{{ __('البيانات الشخصية') }}</flux:heading>

    <x-pages::dashboard.account-settings.layout :heading="__('البيانات الشخصية')" :subheading="__('تحديث بياناتك الشخصية')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('الاسم')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('البريد الإلكتروني')" type="email" required autocomplete="email" />

            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('حفظ') }}
                    </flux:button>
                </div>

            </div>
        </form>
    </x-pages::dashboard.account-settings.layout>
</section>
