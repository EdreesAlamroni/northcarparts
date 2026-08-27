<x-layouts::dashboard :title="__('الأخبار')">
    @php
        $breadcrumbs = [
            ['name' => __('الأخبار'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\News::class)
            <x-actions-section>
                @can('create', \App\Models\News::class)
                    <flux:button :href="route('dashboard.news.create')" variant="primary" size="sm" icon="plus" wire:navigate>
                        {{ __('إضافة خبر جديد') }}
                    </flux:button>
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.news.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $news->total() }})</span>
                            </flux:heading>
                        </x-slot:title>
                    </x-slot:heading>

                    <x-slot:slot>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:input type="text" name="filter[title]" :value="request()->input('filter.title')" autocomplete="off" :placeholder="__('العنوان')" />
                            <flux:input type="text" name="filter[published_at]" :value="request()->input('filter.published_at')" autocomplete="off" :placeholder="__('تاريخ النشر')" x-data="toggleDateFontOnFocus" />
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-start gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">{{ __('بـحـث') }}</flux:button>
                        <flux:button :href="route('dashboard.news.index')" size="sm" icon="arrow-path" wire:navigate>{{ __('مسح حقول التصفية') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('الأخبار') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($news->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('العنوان') }}</th>
                                <th scope="col">{{ __('تاريخ النشر') }}</th>
                                <th scope="col">{{ __('الحالة') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($news as $row)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td>
                                            <span title="{{ $row->title }}">
                                                {{ str($row->title)->limit(50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <x-table-nullable-cell :value="$row->published_at?->toDateString()" class="font-mono" />
                                        </td>
                                        <td>
                                            <x-state-pill :state="$row->state" />
                                        </td>
                                        <td class="actions">
                                            @can('view', $row)
                                                <x-read-more :href="route('dashboard.news.show', $row)" />
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <x-empty-state />
                    @endif
                </x-slot:slot>

                @if ($news->hasPages())
                    <x-slot:footer>
                        {{ $news->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
