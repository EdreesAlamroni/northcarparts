@props([
    'brands' => collect(),
    'values' => [],
])

@php
    $hasError = $errors->has('cross_references') || collect($errors->get('cross_references.*'))->isNotEmpty();
    $hasBrands = $brands->isNotEmpty();
@endphp

<flux:field>
    <flux:label @class(['mb-1!', 'text-red-600' => $hasError])>{{ __('المرجعيات حسب العلامة') }}</flux:label>

    @if (! $hasBrands)
        <x-empty-state
            :text="__('لا توجد علامات تجارية متاحة')"
            :description="__('لم يتم إعداد أي علامة تجارية بعد. يُرجى إضافة العلامات التجارية من إعدادات لوحة التحكم.')"
            icon="bookmark"
        />
    @else
        <div class="overflow-hidden border border-zinc-200 bg-white">
            <div
                class="hidden border-b border-zinc-200 bg-zinc-50/80 px-4 py-2.5 sm:grid sm:grid-cols-[minmax(10rem,18rem)_minmax(0,1fr)] sm:gap-x-6"
                aria-hidden="true"
            >
                <span class="text-xs font-medium text-zinc-500">{{ __('العلامة') }}</span>
                <span class="text-xs font-medium text-zinc-500">{{ __('الرقم المرجعي') }}</span>
            </div>

            <ul class="divide-y divide-zinc-200/80" role="list">
                @foreach ($brands as $brand)
                    @php
                        $inputId = sprintf('cross-reference-%s', $brand->id);
                        $fieldName = sprintf('cross_references.%s', $brand->id);
                        $hasFieldError = $errors->has($fieldName);
                    @endphp
                    <li class="transition-colors hover:bg-zinc-50/40">
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(10rem,18rem)_minmax(0,1fr)] sm:items-center sm:gap-x-6 sm:gap-y-0">
                            <label
                                for="{{ $inputId }}"
                                @class([
                                    'flex min-h-10 items-center rounded-md bg-zinc-50/60 px-4 py-2.5 sm:min-h-12 sm:rounded-none sm:border-e sm:border-zinc-200/80 sm:bg-zinc-50/50 sm:py-0',
                                    'bg-red-50/70 sm:bg-red-50/60' => $hasFieldError,
                                ])
                            >
                                <x-detail-label
                                    :label="$brand->name"
                                    @class(['text-red-600' => $hasFieldError])
                                />
                            </label>

                            <div class="min-w-0 px-4 pb-3 sm:py-3">
                                <div class="w-full">
                                    <flux:input
                                        type="text"
                                        id="{{ $inputId }}"
                                        name="cross_references[{{ $brand->id }}]"
                                        :value="old($fieldName, $values[$brand->id] ?? '')"
                                        autocomplete="off"
                                        lang="en"
                                    />
                                    <flux:error name="cross_references.{{ $brand->id }}" />
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <flux:error name="cross_references" />
</flux:field>
