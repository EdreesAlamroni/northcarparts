<x-layouts::dashboard :title="__('عرض بيانات الخاصية')">
    @php
        $breadcrumbs = [
            ['name' => __('خصائص المنتجات'), 'url' => route('dashboard.specifications.index')],
            ['name' => __('عرض بيانات الخاصية'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['update', 'delete'], $specification)
            <x-actions-section>
                @can('update', $specification)
                    <livewire:pages::dashboard.specifications.create-value :specification="$specification" />
                @endcan

                @can('update', $specification)
                    <livewire:pages::dashboard.specifications.update :specification="$specification" />
                @endcan

                @can('delete', $specification)
                    <x-confirm-delete :action="route('dashboard.specifications.destroy', $specification)" :title="__('حذف الخاصية')" />
                @endcan
            </x-actions-section>
        @endcanany

        <section class="space-y-6">
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="notepad-text" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('عرض بيانات الخاصية') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:field>
                            <x-detail-label :label="__('الاسم')" />
                            <x-detail-value :value="$specification->name" />
                        </flux:field>

                        <flux:field>
                            <x-detail-label :label="__('عدد المنتجات')" />
                            <x-detail-value :value="$specification->products_count" class="font-mono" />
                        </flux:field>
                    </div>
                </x-slot:slot>
            </x-card>

            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:icon name="list-bullet" class="w-4 h-4 shrink-0" />
                        <flux:heading>{{ __('قيم الخاصية') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($specification->values->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('قيمة الخاصية') }}</th>
                                @can('update', $specification)
                                    <th scope="col"></th>
                                @endcan
                            </thead>
                            <tbody>
                                @foreach ($specification->values as $value)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td>{{ $value->value }}</td>
                                        @can('update', $specification)
                                            <td class="actions">
                                                <div class="flex items-center justify-end gap-x-2">
                                                    <livewire:pages::dashboard.specifications.update-value :specification="$specification" :value="$value" :key="'update-'.$value->id" />
                                                    <livewire:pages::dashboard.specifications.delete-value :specification="$specification" :value="$value" :key="'delete-'.$value->id" />
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <x-empty-state />
                    @endif
                </x-slot:slot>
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
