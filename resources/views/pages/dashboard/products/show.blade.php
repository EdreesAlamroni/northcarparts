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
                    <livewire:pages::dashboard.products.upload-images :product="$product" />

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
                    <x-product-specifications-show :specifications="$specifications" />
                </x-slot:slot>
            </x-card>

            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="bookmark-square" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('المراجع الخارجية') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    <x-product-cross-references-show :cross-references="$crossReferences" />
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
                    @php($mediaItems = $product->getMedia('image'))

                    @if ($mediaItems->isNotEmpty())
                        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($mediaItems as $media)
                                <li class="col-span-1 divide-y divide-zinc-200 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
                                    <div class="aspect-[4/3] overflow-hidden bg-zinc-50 p-2">
                                        <img src="{{ $media->getUrl() }}" alt="" class="h-full w-full object-contain" />
                                    </div>

                                    <div class="flex flex-1 flex-col p-4">
                                        <dl class="grid grid-cols-1 gap-2 text-sm">
                                            <div class="flex items-center justify-between gap-x-4">
                                                <dt class="text-zinc-500">{{ __('الحجم') }}</dt>
                                                <dd class="font-mono text-zinc-900">{{ $media->human_readable_size }}</dd>
                                            </div>

                                            <div class="flex items-center justify-between gap-x-4">
                                                <dt class="text-zinc-500">{{ __('النوع') }}</dt>
                                                <dd class="font-mono uppercase text-zinc-900">{{ str($media->extension)->upper() }}</dd>
                                            </div>
                                        </dl>

                                        @can('update', $product)
                                            <div class="mt-4 flex justify-end border-t border-zinc-200 pt-4">
                                                <livewire:pages::dashboard.products.delete-image
                                                    :product="$product"
                                                    :media="$media"
                                                    :key="'delete-image-'.$media->id"
                                                />
                                            </div>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <x-empty-state :text="__('لا توجد صور لهذا المنتج.')" icon="photo" />
                    @endif
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
