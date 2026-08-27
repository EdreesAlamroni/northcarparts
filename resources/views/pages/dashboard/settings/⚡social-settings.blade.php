<?php

use App\Authorization\Settings as SettingsAuthorization;
use App\Settings\SocialSettings;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('الإعدادات'), Layout('layouts::dashboard')] class extends Component
{
    public ?string $facebook_url = null;

    public ?string $instagram_url = null;

    public ?string $linkedin_url = null;

    public function mount(): void
    {
        Gate::authorize('view', SettingsAuthorization::class);

        $settings = app(SocialSettings::class);

        $this->facebook_url = $settings->facebook_url;
        $this->instagram_url = $settings->instagram_url;
        $this->linkedin_url = $settings->linkedin_url;
    }

    public function update(): void
    {
        Gate::authorize('update', SettingsAuthorization::class);

        $validated = $this->validate([
            'facebook_url' => [
                'nullable',
                'url',
                'max:255',
            ],
            'instagram_url' => [
                'nullable',
                'url',
                'max:255',
            ],
            'linkedin_url' => [
                'nullable',
                'url',
                'max:255',
            ],
        ]);

        app(SocialSettings::class)->fill($validated)->save();

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
                        <flux:heading>{{ __('إعدادات مواقع التواصل الاجتماعي') }}</flux:heading>
                    </x-slot:title>
                    <x-slot:description>
                        <x-required-fields-note />
                    </x-slot:description>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('facebook_url')]) for="facebook_url">{{ __('رابط الفيسبوك') }}</flux:label>
                            <flux:input type="url" id="facebook_url" wire:model="facebook_url" autocomplete="off" lang="en" />
                            <flux:error name="facebook_url" />
                        </flux:field>

                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('instagram_url')]) for="instagram_url">{{ __('رابط الإنستغرام') }}</flux:label>
                            <flux:input type="url" id="instagram_url" wire:model="instagram_url" autocomplete="off" lang="en" />
                            <flux:error name="instagram_url" />
                        </flux:field>

                        <flux:field>
                            <flux:label @class(['text-red-600' => $errors->has('linkedin_url')]) for="linkedin_url">{{ __('رابط اللينكدإن') }}</flux:label>
                            <flux:input type="url" id="linkedin_url" wire:model="linkedin_url" autocomplete="off" lang="en" />
                            <flux:error name="linkedin_url" />
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
