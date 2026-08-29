@props([
    'crossReferences' => collect([]),
    'emptyTitle' => __('لا توجد مراجع خارجية لهذا المنتج'),
    'emptyDescription' => __('لم يتم تعيين أي أرقام مرجعية خارجية لهذا المنتج بعد.'),
])

@if ($crossReferences->isNotEmpty())
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        @foreach ($crossReferences as $crossReference)
            <flux:field>
                <x-detail-label :label="$crossReference->brand?->name ?? '-'" />
                <x-detail-value :value="$crossReference->reference_code" class="font-mono" />
            </flux:field>
        @endforeach
    </div>
@else
    <div class="border border-dashed border-zinc-200 bg-zinc-50/50 px-6 py-10 text-center">
        <flux:icon name="bookmark" class="mx-auto mb-3 size-8 text-zinc-400" />

        <p class="text-sm font-medium text-zinc-900">{{ $emptyTitle }}</p>

        <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-zinc-500">
            {{ $emptyDescription }}
        </p>
    </div>
@endif
