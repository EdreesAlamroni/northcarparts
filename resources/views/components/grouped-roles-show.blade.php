@props([
    'groupedRoles' => [],
    'emptyTitle' => __('لا توجد أدوار مُخصصة'),
    'emptyDescription' => __('لم يتم تعيين أي أدوار أو صلاحيات لهذا المُستخدم بعد. يمكنك تعديل بيانات المستخدم لإضافة الصلاحيات المناسبة.'),
])

@php
    $hasRoles = collect($groupedRoles)->contains(
        fn (object $group): bool => $group->roles->isNotEmpty(),
    );
@endphp

@if ($hasRoles)
    <div class="grid grid-cols-1 gap-8">
        @foreach ($groupedRoles as $group)
            <section aria-labelledby="role-group-{{ $group->key }}" class="space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5">
                    <flux:heading
                        id="role-group-{{ $group->key }}"
                        size="sm"
                        class="font-medium"
                    >{{ $group->label }}</flux:heading>
                </div>

                @if ($group->roles->isNotEmpty())
                    <ul class="grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-3">
                        @foreach ($group->roles as $role)
                            <li class="flex items-center gap-x-2">
                                <flux:icon
                                    name="circle-check"
                                    variant="micro"
                                    class="size-4 shrink-0 text-accent"
                                />
                                <span class="text-sm font-normal">{{ $role->label }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-zinc-500">
                        {{ __('لا توجد صلاحيات في هذه المجموعة.') }}
                    </p>
                @endif
            </section>
        @endforeach
    </div>
@else
    <div class="border border-dashed border-zinc-200 bg-zinc-50/50 px-6 py-10 text-center">
        <flux:icon name="shield" class="mx-auto mb-3 size-8 text-zinc-400" />

        <p class="text-sm font-medium text-zinc-900">{{ $emptyTitle }}</p>

        <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-zinc-500">
            {{ $emptyDescription }}
        </p>
    </div>
@endif
