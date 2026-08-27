<?php

use App\Authorization\Settings as SettingsAuthorization;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('الإعدادات'), Layout('layouts::dashboard')] class extends Component
{
    public string $company_name = '';

    public ?string $email = null;

    public ?string $phone_number = null;

    public ?string $address = null;

    public function mount(): void
    {
        Gate::authorize('view', SettingsAuthorization::class);

        $settings = app(GeneralSettings::class);

        $this->company_name = $settings->company_name;
        $this->email = $settings->email;
        $this->phone_number = $settings->phone_number;
        $this->address = $settings->address;
    }

    public function update(): void
    {
        Gate::authorize('update', SettingsAuthorization::class);

        $validated = $this->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
        ]);

        app(GeneralSettings::class)->fill($validated)->save();

        toast_success('update');
    }
}; ?>

@php
    $breadcrumbs = [
        ['name' => __('الإعدادات'), 'active' => true],
    ];
@endphp

<x-slot:breadcrumbs>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
</x-slot:breadcrumbs>

<x-slot:mobile-breadcrumbs>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
</x-slot:mobile-breadcrumbs>

<x-pages::dashboard.settings.layout>
    <section>
        <form wire:submit="update">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('الإعدادات العامة') }}</flux:heading>
                    </x-slot:title>
                    <x-slot:description>
                        <x-required-fields-note />
                    </x-slot:description>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('company_name')]) for="company_name" badge="*" required>{{ __('اسم الشركة') }}</flux:label>
                            <flux:input type="text" id="company_name" wire:model="company_name" autocomplete="off" required />
                            <flux:error name="company_name" />
                        </flux:field>

                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('email')]) for="email">{{ __('البريد الإلكتروني') }}</flux:label>
                            <flux:input type="email" id="email" wire:model="email" autocomplete="off" lang="en" />
                            <flux:error name="email" />
                        </flux:field>

                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('phone_number')]) for="phone_number">{{ __('الهاتف') }}</flux:label>
                            <flux:input type="tel" id="phone_number" wire:model="phone_number" autocomplete="off" lang="en" maxlength="10" x-data="sanitizePhoneNumberInput" />
                            <flux:error name="phone_number" />
                        </flux:field>

                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('address')]) for="address">{{ __('العنوان') }}</flux:label>
                            <flux:input type="text" id="address" wire:model="address" autocomplete="off" />
                            <flux:error name="address" />
                        </flux:field>
                    </div>
                </x-slot:slot>

                @can('update', \App\Authorization\Settings::class)
                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('تـحـديـث') }}</flux:button>
                    </x-slot:footer>
                @endcan
            </x-card>
        </form>
    </section>
</x-pages::dashboard.settings.layout>
