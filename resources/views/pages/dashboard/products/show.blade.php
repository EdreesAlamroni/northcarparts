<x-layouts::dashboard :title="__('عرض بيانات المنتج')">
    @php
        $breadcrumbs = [
            ['name' => __('المنتجات'), 'url' => route('dashboard.products.index')],
            ['name' => __('عرض بيانات المنتج'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete', 'stateUpdate'], $product)
            <x-actions-section>
                @can('stateUpdate', $product)
                    <livewire:pages::dashboard.products.state-update :product="$product" />
                @endcan

                @can('update', $product)
                    <livewire:pages::dashboard.products.update-image :product="$product" />

                    <flux:button :href="route('dashboard.products.edit', $product)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>{{ __('تعديل بيانات المنتج') }}</flux:button>
                @endcan

                @can('delete', $product)
                    <x-confirm-delete :action="route('dashboard.products.destroy', $product)" :title="__('حذف المنتج')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="cube" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات المنتج') }}</flux:heading>
                    </x-slot:title>

                    <x-slot:actions>
                        <x-state-pill :state="$product->state" />
                    </x-slot:actions>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field>
                            <x-detail-label :label="__('كود الفلتر')" />
                            <x-detail-value :value="$product->code" class="font-mono" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('الرابط (Slug)')" />
                            <x-detail-slug-value :slug="$product->slug" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('التصنيف')" />
                            <x-detail-value :value="$product->category?->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('الشركة المصنعة OEM')" />
                            <x-detail-value :value="$product->manufacturer?->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('رقم OEM')" />
                            <x-detail-value :value="$product->oem_number" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('ترتيب العرض')" />
                            <x-detail-value :value="$product->sort_order" class="font-mono" />
                        </flux:field>
                    </div>
                </x-slot:slot>
            </x-card>

            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="clipboard-document-list" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('الخصائص') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    <x-grouped-specifications-show :grouped-specifications="$groupedSpecifications" />
                </x-slot:slot>
            </x-card>

            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="photo" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('الصور') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot>
                    @if ($product->getFirstMediaUrl('image') || $product->getFirstMediaUrl('qr_code'))
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @if ($product->getFirstMediaUrl('image'))
                                <flux:field>
                                    <x-detail-label :label="__('صورة المنتج')" />
                                    <div class="h-48 w-full p-1 rounded-lg border border-zinc-200 object-contain">
                                        <img
                                            src="{{ $product->getFirstMediaUrl('image') }}"
                                            alt="{{ $product->code }}"
                                            class="w-full h-full object-contain"
                                        />
                                    </div >
                                </flux:field>
                            @endif

                            @if ($product->getFirstMediaUrl('qr_code'))
                                <flux:field>
                                    <x-detail-label :label="__('رمز QR')" />
                                    <div class="h-48 w-full p-1 rounded-lg border border-zinc-200 object-contain">
                                        <img
                                            src="{{ $product->getFirstMediaUrl('qr_code') }}"
                                            alt="{{ __('رمز QR') }}"
                                            class="w-full h-full object-contain"
                                        />
                                    </div>
                                </flux:field>
                            @endif
                        </div>
                    @else
                        <x-empty-state
                            :text="__('لا توجد صور لهذا المنتج.')"
                            icon="photo"
                        />
                    @endif
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
