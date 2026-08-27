<x-layouts::dashboard :title="__('عرض بيانات الشركة المصنعة')">
    @php
        $breadcrumbs = [
            ['name' => __('الشركات المصنعة'), 'url' => route('dashboard.manufacturers.index')],
            ['name' => __('عرض بيانات الشركة المصنعة'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete'], $manufacturer)
            <x-actions-section>
                @can('update', $manufacturer)
                    <flux:button :href="route('dashboard.manufacturers.edit', $manufacturer)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>{{ __('تعديل بيانات الشركة المصنعة') }}</flux:button>
                @endcan

                @can('delete', $manufacturer)
                    <x-confirm-delete :action="route('dashboard.manufacturers.destroy', $manufacturer)" :title="__('حذف الشركة المصنعة')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="notepad-text" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات الشركة المصنعة') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field>
                            <x-detail-label :label="__('الاسم')" />
                            <x-detail-value :value="$manufacturer->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('عدد المنتجات')" />
                            <x-detail-value :value="$manufacturer->products_count" class="font-mono" />
                        </flux:field>
                    </div>
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
