<x-layouts::dashboard :title="__('عرض بيانات العلامة التجارية')">
    @php
        $breadcrumbs = [
            ['name' => __('العلامات التجارية'), 'url' => route('dashboard.brands.index')],
            ['name' => __('عرض بيانات العلامة التجارية'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete'], $brand)
            <x-actions-section>
                @can('update', $brand)
                    <flux:button :href="route('dashboard.brands.edit', $brand)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>{{ __('تعديل بيانات العلامة التجارية') }}</flux:button>
                @endcan

                @can('delete', $brand)
                    <x-confirm-delete :action="route('dashboard.brands.destroy', $brand)" :title="__('حذف العلامة التجارية')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="bookmark-square" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات العلامة التجارية') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field>
                            <x-detail-label :label="__('الاسم')" />
                            <x-detail-value :value="$brand->name" class="font-mono" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('عدد المنتجات')" />
                            <x-detail-value :value="$brand->products_count" class="font-mono" />
                        </flux:field>
                    </div>
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
