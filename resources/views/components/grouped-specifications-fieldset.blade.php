@props([
    'groupedSpecifications' => [],
    'selectedGroups' => [],
    'selectedValueIds' => [],
])

@php
    $selectedGroups = collect(old('specification_groups', $selectedGroups))
        ->map(fn (mixed $id): string => (string) $id)
        ->values()
        ->all();
    $selectedValueIds = collect(old('specification_value_ids', $selectedValueIds))
        ->map(fn (mixed $id): string => (string) $id)
        ->values()
        ->all();
    $hasError = $errors->has('specification_value_ids') || $errors->has('specification_groups');
    $hasSpecifications = count($groupedSpecifications) > 0;

    $groupsForAlpine = collect($groupedSpecifications)
        ->map(function ($group) {
            return [
                'key' => $group->key,
                'values' => $group->values->map(fn ($value): array => [
                    'id' => $value->id,
                    'label' => $value->label,
                ])->all(),
            ];
        })
        ->values()
        ->all();
@endphp

<flux:field>
    <flux:label
        @class(['text-red-600' => $hasError])
    >{{ __('خصائص المنتج') }}</flux:label>

    @if (! $hasSpecifications)
        <x-empty-state
            :text="__('لا توجد خصائص متاحة')"
            :description="__('لم يتم إعداد أي خصائص بعد. يُرجى إضافة الخصائص من إدارة خصائص المنتجات.')"
            icon="clipboard-document-list"
        />
    @else
        <div
            x-data="groupedSpecificationsFieldset({
                selectedGroups: @js($selectedGroups),
                selectedValues: @js($selectedValueIds),
                groups: @js($groupsForAlpine),
            })"
            class="space-y-6"
        >
            <section class="rounded-lg border border-zinc-200 p-4">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-200 pb-2.5">
                    <flux:heading size="sm" class="font-medium">{{ __('اختر الخصائص') }}</flux:heading>

                    <div data-select-all="global-groups">
                        <flux:checkbox
                            :label="__('تحديد جميع الخصائص')"
                            :invalid="$hasError"
                            x-on:change="toggleAllGroups($event)"
                        />
                    </div>
                </div>

                <ul class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-3">
                    @foreach ($groupedSpecifications as $group)
                        <li>
                            <div data-select-group="{{ $group->key }}">
                                <flux:checkbox
                                    name="specification_groups[]"
                                    :label="$group->label"
                                    :value="$group->key"
                                    :checked="in_array($group->key, $selectedGroups, true)"
                                    :invalid="$hasError"
                                    x-on:change="toggleGroupSelection('{{ $group->key }}', $event)"
                                />
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            <div class="grid grid-cols-1 gap-6">
                @foreach ($groupedSpecifications as $group)
                    <section
                        aria-labelledby="specification-group-{{ $group->key }}"
                        class="rounded-lg border border-zinc-200 p-4"
                        x-show="isGroupSelected('{{ $group->key }}')"
                        x-cloak
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-200 pb-2.5">
                            <flux:heading
                                id="specification-group-{{ $group->key }}"
                                size="sm"
                                class="font-medium"
                            >{{ $group->label }}</flux:heading>

                            @if ($group->values->isNotEmpty())
                                <div data-select-all="group-values" data-group-key="{{ $group->key }}">
                                    <flux:checkbox
                                        :label="__('تحديد الكل')"
                                        x-on:change="toggleAllValuesInGroup('{{ $group->key }}', $event)"
                                    />
                                </div>
                            @endif
                        </div>

                        @if ($group->values->isEmpty())
                            <p class="mt-4 text-sm text-zinc-500">
                                {{ __('لا توجد قيم متاحة لهذه الخاصية.') }}
                            </p>
                        @else
                            <ul class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-3">
                                @foreach ($group->values as $value)
                                    <li>
                                        <flux:checkbox
                                            name="specification_value_ids[]"
                                            :label="$value->label"
                                            :value="(string) $value->id"
                                            :checked="in_array((string) $value->id, $selectedValueIds, true)"
                                            :invalid="$hasError"
                                            x-on:change="toggleValue($event)"
                                        />
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endforeach
            </div>
        </div>
    @endif

    <flux:error name="specification_value_ids" />
    <flux:error name="specification_groups" />
</flux:field>
