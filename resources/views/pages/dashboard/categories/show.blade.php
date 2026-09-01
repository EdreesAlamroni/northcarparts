<x-layouts::dashboard :title="__('عرض بيانات التصنيف')">
    @php
        $breadcrumbs = [
            ['name' => __('التصنيفات'), 'url' => route('dashboard.categories.index')],
            ['name' => __('عرض بيانات التصنيف'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete', 'stateUpdate'], $category)
            <x-actions-section>
                @can('stateUpdate', $category)
                    <livewire:pages::dashboard.categories.state-update :category="$category" />
                @endcan

                @can('update', $category)
                    <livewire:pages::dashboard.categories.update-image :category="$category" />

                    <flux:button :href="route('dashboard.categories.edit', $category)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>{{ __('تعديل بيانات التصنيف') }}</flux:button>
                @endcan

                @can('delete', $category)
                    <x-confirm-delete :action="route('dashboard.categories.destroy', $category)" :title="__('حذف التصنيف')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="squares-2x2" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات التصنيف') }}</flux:heading>
                    </x-slot:title>

                    <x-slot:actions>
                        <x-state-pill :state="$category->state" />
                    </x-slot:actions>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field class="col-span-full">
                            <x-detail-label :label="__('الاسم')" />
                            <x-detail-value :value="$category->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('الرابط (Slug)')" />
                            <x-detail-slug-value :slug="$category->slug" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('ترتيب العرض')" />
                            <x-detail-value :value="$category->sort_order" class="font-mono" />
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <flux:field>
                            <x-detail-label :label="__('الوصف')" />
                            <x-detail-value :value="$category->description" html />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('الوصف التقني')" />
                            <x-detail-value :value="$category->technical_description" html />
                        </flux:field>
                    </div>

                    @if ($category->getFirstMediaUrl('image'))
                        <flux:field>
                            <x-detail-label :label="__('الصورة')" />
                            <img src="{{ $category->getFirstMediaUrl('image') }}" alt="{{ $category->name }}" class="max-h-48 rounded-lg border border-zinc-200" />
                        </flux:field>
                    @endif
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
