<x-layouts::dashboard :title="__('إضافة مستخدم جديد')">
    @php
        $breadcrumbs = [
            ['name' => __('المستخدمين'), 'url' => route('dashboard.users.index')],
            ['name' => __('إضافة مستخدم جديد'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        <x-validation-errors :errors="$errors" />

        <section>
            <form action="{{ route('dashboard.users.store') }}" method="POST" class="non-wire">
                @csrf

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('إضافة مستخدم جديد') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label
                                    @class(['text-red-600' => $errors->has('name')])
                                    for="name"
                                    badge="*"
                                    required
                                >{{ __('الاسم') }}</flux:label>
                                <flux:input
                                    type="text"
                                    id="name"
                                    name="name"
                                    :value="old('name')"
                                    autocomplete="off"
                                    required
                                />
                                <flux:error name="name" />
                            </flux:field>

                            <flux:field>
                                <flux:label
                                    @class(['text-red-600' => $errors->has('email')])
                                    for="email"
                                    badge="*"
                                    required
                                >{{ __('البريد الإلكتروني') }}</flux:label>
                                <flux:input
                                    type="email"
                                    id="email"
                                    name="email"
                                    :value="old('email')"
                                    autocomplete="off"
                                    required
                                    lang="en"
                                />
                                <flux:error name="email" />
                            </flux:field>

                            <flux:field>
                                <flux:label
                                    @class(['text-red-600' => $errors->has('password')])
                                    for="password"
                                    badge="*"
                                    required
                                >{{ __('كلمة المرور') }}</flux:label>
                                <flux:input
                                    type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    viewable
                                />
                                <flux:error name="password" />
                            </flux:field>

                            <flux:field>
                                <flux:label
                                    @class(['text-red-600' => $errors->has('password_confirmation')])
                                    for="password_confirmation"
                                    badge="*"
                                    required
                                >{{ __('تأكيد كلمة المرور') }}</flux:label>
                                <flux:input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                    viewable
                                />
                                <flux:error name="password_confirmation" />
                            </flux:field>
                        </div>

                        <x-grouped-roles-fieldset :grouped-roles="$groupedRoles" />
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.users.index')" size="sm" icon="arrow-uturn-left" wire:navigate>
                            {{ __('إلغاء الأمر') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">
                            {{ __('إضـافـة') }}
                        </flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
