<x-layouts::dashboard :title="__('المنتجات')">
    @php
        $breadcrumbs = [
            ['name' => __('المنتجات'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\Product::class)
            <x-actions-section>
                @can('create', \App\Models\Product::class)
                    <flux:button :href="route('dashboard.products.create')" variant="primary" size="sm" icon="plus" wire:navigate>
                        {{ __('إضافة منتج جديد') }}
                    </flux:button>
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.products.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $products->total() }})</span>
                            </flux:heading>
                        </x-slot:title>
                    </x-slot:heading>

                    <x-slot:slot>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:input type="text" name="filter[code]" :value="request()->input('filter.code')" autocomplete="off" lang="en" :placeholder="__('الكود')" />
                            <flux:input type="text" name="filter[name]" :value="request()->input('filter.name')" autocomplete="off" :placeholder="__('الاسم')" />
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-start gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">{{ __('بـحـث') }}</flux:button>
                        <flux:button :href="route('dashboard.products.index')" size="sm" icon="arrow-path" wire:navigate>{{ __('مسح حقول التصفية') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('المنتجات') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($products->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('الكود') }}</th>
                                <th scope="col">{{ __('الاسم') }}</th>
                                <th scope="col">{{ __('التصنيف') }}</th>
                                <th scope="col">{{ __('الحالة') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td class="font-mono">{{ $product->code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <x-table-nullable-cell :value="$product->category?->name" />
                                        </td>
                                        <td>
                                            <x-state-pill :state="$product->state" />
                                        </td>
                                        <td class="actions">
                                            @can('view', $product)
                                                <x-read-more :href="route('dashboard.products.show', $product)" />
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

                @if ($products->hasPages())
                    <x-slot:footer>
                        {{ $products->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
