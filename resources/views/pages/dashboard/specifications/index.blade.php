<x-layouts::dashboard :title="__('خصائص المنتجات')">
    @php
        $breadcrumbs = [
            ['name' => __('خصائص المنتجات'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\Specification::class)
            <x-actions-section>
                @can('create', \App\Models\Specification::class)
                    <livewire:pages::dashboard.specifications.create />
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.specifications.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $specifications->total() }})</span>
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
                        <flux:button :href="route('dashboard.specifications.index')" size="sm" icon="arrow-path" wire:navigate>{{ __('مسح حقول التصفية') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('خصائص المنتجات') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($specifications->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('الاسم') }}</th>
                                <th scope="col" class="text-center">{{ __('عدد المنتجات') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($specifications as $specification)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td>{{ $specification->name }}</td>
                                        <td class="font-mono text-center">{{ $specification->products_count }}</td>
                                        @canany(['update', 'delete'], $specification)
                                            <td class="actions">
                                                <div class="flex items-center justify-end gap-x-3">
                                                    @can('update', $specification)
                                                        <livewire:pages::dashboard.specifications.update
                                                            :specification="$specification"
                                                            :wire:key="'specification-update-'.$specification->id"
                                                        />
                                                    @endcan
                                                    @can('delete', $specification)
                                                        <livewire:pages::dashboard.specifications.delete
                                                            :specification="$specification"
                                                            :compact="true"
                                                            :wire:key="'specification-delete-'.$specification->id"
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

                @if ($specifications->hasPages())
                    <x-slot:footer>
                        {{ $specifications->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
