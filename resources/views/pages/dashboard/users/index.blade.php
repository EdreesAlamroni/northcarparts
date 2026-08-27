<x-layouts::dashboard :title="__('المستخدمين')">
    @php
        $breadcrumbs = [
            ['name' => __('المستخدمين'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        @canany(['create'], \App\Models\User::class)
            <x-actions-section>
                @can('create', \App\Models\User::class)
                    <flux:button
                        :href="route('dashboard.users.create')"
                        variant="primary"
                        size="sm"
                        icon="plus"
                        wire:navigate
                    >
                        {{ __('إضافة مستخدم جديد') }}
                    </flux:button>
                @endcan
            </x-actions-section>
        @endcanany

        <section>
            <form action="{{ route('dashboard.users.index') }}" method="GET" wire:navigate>
                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading class="flex items-center gap-x-2">
                                <flux:icon name="funnel" variant="outline" class="h-4 w-4 shrink-0" />
                                <span>{{ __('فلترة النتائج') }}</span>
                                <span class="font-mono">({{ $users->total() }})</span>
                            </flux:heading>
                        </x-slot:title>
                    </x-slot:heading>

                    <x-slot:slot>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:input
                                type="text"
                                name="filter[name]"
                                :value="request()->input('filter.name')"
                                autocomplete="off"
                                :placeholder="__('الاسم')"
                            />

                            <flux:input
                                type="email"
                                name="filter[email]"
                                :value="request()->input('filter.email')"
                                autocomplete="off"
                                lang="en"
                                :placeholder="__('البريد الإلكتروني')"
                            />
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-start gap-x-3">
                        <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">
                            {{ __('بـحـث') }}
                        </flux:button>

                        <flux:button :href="route('dashboard.users.index')" size="sm" icon="arrow-path" wire:navigate>
                            {{ __('مسح حقول التصفية') }}
                        </flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>

        <section>
            <x-card>
                <x-slot:heading>
                    <x-slot:title>
                        <flux:heading>{{ __('المستخدمين') }}</flux:heading>
                    </x-slot:title>
                </x-slot:heading>

                <x-slot:slot class="overflow-x-auto">
                    @if ($users->isNotEmpty())
                        <table>
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('الاسم') }}</th>
                                <th scope="col">{{ __('البريد الإلكتروني') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="font-mono">{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <x-email-link :value="$user->email" />
                                        </td>
                                        <td class="actions">
                                            @can('view', $user)
                                                <x-read-more :href="route('dashboard.users.show', $user)" />
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

                @if ($users->hasPages())
                    <x-slot:footer>
                        {{ $users->links() }}
                    </x-slot:footer>
                @endif
            </x-card>
        </section>
    </div>
</x-layouts::dashboard>
