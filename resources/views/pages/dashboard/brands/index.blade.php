<x-layouts::dashboard :title="__('العلامات التجارية')">
    @php
        $breadcrumbs = [
            ['name' => __('العلامات التجارية'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\Brand::class)
            <x-actions-section>
                @can('create', \App\Models\Brand::class)
                    <flux:button :href="route('dashboard.brands.create')" variant="primary" size="sm" icon="plus" wire:navigate>
                        {{ __('إضافة علامة تجارية جديدة') }}
                    </flux:button>
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.brands.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $brands->total() }})</span>
                            </flux:heading>
                        </x-slot:title>
                    </x-slot:heading>

                    <x-slot:slot>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:input type="text" name="filter[name]" :value="request()->input('filter.name')" autocomplete="off" :placeholder="__('اسم العلامة التجارية')" />
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-start gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">{{ __('بـحـث') }}</flux:button>
                        <flux:button :href="route('dashboard.brands.index')" size="sm" icon="arrow-path" wire:navigate>{{ __('مسح حقول التصفية') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('العلامات التجارية') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($brands->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('الاسم') }}</th>
                                <th scope="col" class="text-center">{{ __('عدد المنتجات') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td class="font-mono">{{ $brand->name }}</td>
                                        <td class="font-mono text-center">{{ $brand->products_count }}</td>
                                        <td class="actions">
                                            @can('view', $brand)
                                                <x-read-more :href="route('dashboard.brands.show', $brand)" />
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

                @if ($brands->hasPages())
                    <x-slot:footer>
                        {{ $brands->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
