<x-layouts::dashboard :title="__('المستخدمين')">
    @php
        $breadcrumbs = [
            ['name' => __('المستخدمين'), 'url' => route('dashboard.users.index')],
            ['name' => __('عرض بيانات المستخدم'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete'], $user)
            <x-actions-section>
                @can('update', $user)
                    <livewire:pages::dashboard.users.change-password :user="$user" />
                @endcan

                @can('update', $user)
                    <flux:button :href="route('dashboard.users.edit', $user)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>
                        {{ __('تعديل بيانات المستخدم') }}
                    </flux:button>
                @endcan

                @can('delete', $user)
                    <x-confirm-delete :action="route('dashboard.users.destroy', $user)" :title="__('حذف المستخدم')" />
                @endcan
            </x-actions-section>
        @endcanany


        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="notepad-text" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات المستخدم') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field>
                            <x-detail-label :label="__('الاسم')" />
                            <x-detail-value :value="$user->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('البريد الإلكتروني')" />
                            <x-detail-value>
                                <x-email-link :value="$user->email" />
                            </x-detail-value>
                        </flux:field>
                    </div>
                </x-slot:slot>
            </x-card>

            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="shield" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('الأدوار والصلاحيات') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot>
                    <x-grouped-roles-show :grouped-roles="$groupedRoles" />
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
