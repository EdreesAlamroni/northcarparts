@props([
    'groupedSpecifications' => [],
    'emptyTitle' => __('لا توجد خصائص لهذا المنتج'),
    'emptyDescription' => __('لم يتم تعيين أي خصائص لهذا المنتج بعد. يمكنك تعديل بيانات المنتج لإضافة الخصائص المناسبة.'),
])

@php
    $hasSpecifications = collect($groupedSpecifications)->contains(
        fn (object $group): bool => $group->values->isNotEmpty(),
    );
@endphp

@if ($hasSpecifications)
    <div class="grid grid-cols-1 gap-8">
        @foreach ($groupedSpecifications as $group)
            <section aria-labelledby="specification-group-{{ $group->key }}" class="space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-200 pb-2.5">
                    <flux:heading
                        id="specification-group-{{ $group->key }}"
                        size="sm"
                        class="font-medium"
                    >{{ $group->label }}</flux:heading>
                </div>

                @if ($group->values->isNotEmpty())
                    <ul class="grid grid-cols-1 gap-3 lg:grid-cols-2 xl:grid-cols-3">
                        @foreach ($group->values as $value)
                            <li class="flex items-center gap-x-2">
                                <flux:icon
                                    name="circle-check"
                                    variant="micro"
                                    class="size-4 shrink-0 text-accent"
                                />
                                <span class="text-sm font-normal">{{ $value->label }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-zinc-500">
                        {{ __('لا توجد قيم متاحة لهذه الخاصية.') }}
                    </p>
                @endif
            </section>
        @endforeach
    </div>
@else
    <div class="border border-dashed border-zinc-200 bg-zinc-50/50 px-6 py-10 text-center">
        <flux:icon name="clipboard-document-list" class="mx-auto mb-3 size-8 text-zinc-400" />

        <p class="text-sm font-medium text-zinc-900">{{ $emptyTitle }}</p>

        <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-zinc-500">
            {{ $emptyDescription }}
        </p>
    </div>
@endif
