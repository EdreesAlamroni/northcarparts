@php
    $specifications = $specifications ?? collect();
    $emptyTitle = $emptyTitle ?? __('لا توجد خصائص لهذا المنتج');
    $emptyDescription = $emptyDescription ?? __('لم يتم تعيين أي خصائص لهذا المنتج بعد. يمكنك تعديل بيانات المنتج لإضافة الخصائص المناسبة.');
@endphp

@if ($specifications->isNotEmpty())
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        @foreach ($specifications as $specification)
            <flux:field>
                <x-detail-label :label="$specification->name" />
                <x-detail-value :value="$specification->pivot->value" />
            </flux:field>
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
