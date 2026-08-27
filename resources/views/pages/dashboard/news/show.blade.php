<x-layouts::dashboard :title="__('عرض بيانات الخبر')">
    @php
        $breadcrumbs = [
            ['name' => __('الأخبار'), 'url' => route('dashboard.news.index')],
            ['name' => __('عرض بيانات الخبر'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete', 'stateUpdate'], $news)
            <x-actions-section>
                @can('stateUpdate', $news)
                    <livewire:pages::dashboard.news.state-update :news="$news" />
                @endcan

                @can('update', $news)
                    <livewire:pages::dashboard.news.update-image :news="$news" />

                    <flux:button :href="route('dashboard.news.edit', $news)" size="sm" icon="pencil-square" iconVariant="outline" wire:navigate>{{ __('تعديل بيانات الخبر') }}</flux:button>
                @endcan

                @can('delete', $news)
                    <x-confirm-delete :action="route('dashboard.news.destroy', $news)" :title="__('حذف الخبر')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="newspaper" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات الخبر') }}</flux:heading>
                    </x-slot:title>

                    <x-slot:actions>
                        <x-state-pill :state="$news->state" />
                    </x-slot:actions>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field class="col-span-full">
                            <x-detail-label :label="__('العنوان')" />
                            <x-detail-value :value="$news->title" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('الرابط')" />
                            <x-detail-slug-value :slug="$news->slug" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('تاريخ النشر')" />
                            <x-detail-value :value="$news->published_at?->format('Y-m-d')" class="font-mono" />
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <flux:field>
                            <x-detail-label :label="__('المحتوى')" />
                            <x-detail-value :value="$news->content" />
                        </flux:field>

                        @if ($news->getFirstMediaUrl('image'))
                            <flux:field>
                                <x-detail-label :label="__('الصورة')" />
                                <img src="{{ $news->getFirstMediaUrl('image') }}" alt="{{ $news->title }}" class="max-h-48 rounded-lg border border-zinc-200" />
                            </flux:field>
                        @endif
                    </div>
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
