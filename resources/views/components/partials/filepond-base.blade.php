@php
    use Illuminate\Support\Str;

    $id ??= 'filepond-'.Str::uuid();

    $acceptedFileTypes = match (true) {
        is_array($accept) => array_values(array_filter($accept)),
        is_string($accept) => array_values(array_filter(explode(',', str_replace(' ', '', $accept)))),
        $attributes->has('accept') => array_values(array_filter(explode(',', str_replace(' ', '', $attributes->get('accept'))))),
        default => [],
    };

    $inputName = $name ?? $attributes->get('name');
    $maxFileSize ??= allowedImageMaxFileSize();
    $allowMultiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);
    $allowImagePreview = filter_var($allowImagePreview, FILTER_VALIDATE_BOOLEAN);

    if ($allowImagePreview && count($acceptedFileTypes) > 0) {
        $allowImagePreview = collect($acceptedFileTypes)->contains(
            fn (string $type): bool => str_starts_with($type, 'image/')
        );
    }

    $filepondConfig = [
        'acceptedFileTypes' => $acceptedFileTypes,
        'allowMultiple' => $allowMultiple,
        'allowImagePreview' => $allowImagePreview,
        'maxFileSize' => $maxFileSize,
        'livewire' => (bool) $livewire,
        'uploadProperty' => $inputName,
    ];

    $rootAttributes = $attributes
        ->only(['class', 'wire:key'])
        ->class('w-full');

    $inputAttributes = $attributes->except([
        'accept',
        'class',
        'id',
        'multiple',
        'name',
        'wire:key',
    ]);
@endphp

<div
    @if ($livewire) wire:ignore @endif
    @if ($livewire)
        x-data="filepondInput(@js($filepondConfig), $wire)"
    @else
        x-data="filepondInput(@js($filepondConfig))"
    @endif
    {{ $rootAttributes }}
>
    <input
        type="file"
        id="{{ $id }}"
        x-ref="input"
        @if ($allowMultiple) multiple @endif
        @if (! $livewire && filled($inputName)) name="{{ $inputName }}" @endif
        @if (count($acceptedFileTypes) > 0) accept="{{ implode(',', $acceptedFileTypes) }}" @endif
        {{ $inputAttributes }}
    />
</div>
