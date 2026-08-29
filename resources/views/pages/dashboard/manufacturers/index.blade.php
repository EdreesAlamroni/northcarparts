<x-layouts::dashboard :title="__('الشركات المصنعة')">
    @php
        $breadcrumbs = [
            ['name' => __('الشركات المصنعة'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\Manufacturer::class)
            <x-actions-section>
                @can('create', \App\Models\Manufacturer::class)
                    <livewire:pages::dashboard.manufacturers.create />
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.manufacturers.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $manufacturers->total() }})</span>
                            </flux:heading>
                        </x-slot:title>
                    </x-slot:heading>

                    <x-slot:slot>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:input type="text" name="filter[name]" :value="request()->input('filter.name')" autocomplete="off" :placeholder="__('الاسم')" />
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-start gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">{{ __('بـحـث') }}</flux:button>
                        <flux:button :href="route('dashboard.manufacturers.index')" size="sm" icon="arrow-path" wire:navigate>{{ __('مسح حقول التصفية') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('الشركات المصنعة') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($manufacturers->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('الاسم') }}</th>
                                <th scope="col" class="text-center">{{ __('عدد المنتجات') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($manufacturers as $manufacturer)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td>{{ $manufacturer->name }}</td>
                                        <td class="font-mono text-center">{{ $manufacturer->products_count }}</td>
                                        @canany(['update', 'delete'], $manufacturer)
                                            <td class="actions">
                                                <div class="flex items-center justify-end gap-x-3">
                                                    @can('update', $manufacturer)
                                                        <livewire:pages::dashboard.manufacturers.update
                                                            :manufacturer="$manufacturer"
                                                            :wire:key="'manufacturer-update-'.$manufacturer->id"
                                                        />
                                                    @endcan
                                                    @can('delete', $manufacturer)
                                                        <livewire:pages::dashboard.manufacturers.delete
                                                            :manufacturer="$manufacturer"
                                                            :wire:key="'manufacturer-delete-'.$manufacturer->id"
                                                        />
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <x-empty-state />
                    @endif
                </x-slot:slot>

                @if ($manufacturers->hasPages())
                    <x-slot:footer>
                        {{ $manufacturers->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
