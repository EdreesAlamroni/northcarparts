@props([
    'groupedRoles' => [],
    'selectedRoles' => [],
])

@php
    $selectedRoles = old('roles', $selectedRoles);
    $hasError = $errors->has('roles');
    $hasRoles = count($groupedRoles) > 0;

    $groupsForAlpine = collect($groupedRoles)
        ->map(function ($group) {
            return [
                'key' => $group->key,
                'roles' => $group->roles->pluck('name')->all(),
            ];
        })
        ->values()
        ->all();
@endphp

<flux:field>
    <flux:label
        @class(['text-red-600' => $hasError])
        badge="*"
        required
    >{{ __('الأدوار والصلاحيات') }}</flux:label>

    @if (! $hasRoles)
        <x-empty-state
            :text="__('لا توجد أدوار متاحة')"
            :description="__('لم يتم إعداد أي أدوار بعد. يُرجى التواصل مع مسؤول النظام.')"
            icon="shield-check"
        />
    @else
        <div
            x-data="groupedRolesFieldset({
                selected: @js($selectedRoles),
                groups: @js($groupsForAlpine),
            })"
            class="space-y-3"
        >
            <div data-select-all="global" class="mt-1">
                <flux:checkbox
                    :label="__('تحديد جميع الأدوار والصلاحيات')"
                    :invalid="$hasError"
                    x-on:change="toggleAll($event)"
                />
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach ($groupedRoles as $group)
                    <section
                        aria-labelledby="role-group-{{ $group->key }}"
                        class="rounded-lg border border-zinc-200 p-4"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-200 pb-2.5">
                            <flux:heading
                                id="role-group-{{ $group->key }}"
                                size="sm"
                                class="font-medium"
                            >{{ $group->label }}</flux:heading>

                            <div data-select-all="group" data-group-key="{{ $group->key }}">
                                <flux:checkbox
                                    :label="__('تحديد الكل')"
                                    x-on:change="toggleGroup('{{ $group->key }}', $event)"
                                />
                            </div>
                        </div>

                        <ul class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($group->roles as $role)
                                <li>
                                    <flux:checkbox
                                        name="roles[]"
                                        :label="$role->label"
                                        :value="$role->name"
                                        :checked="in_array($role->name, $selectedRoles, true)"
                                        :invalid="$hasError"
                                        x-on:change="toggleRole($event)"
                                    />
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        </div>
    @endif

    <flux:error name="roles" />
</flux:field>
