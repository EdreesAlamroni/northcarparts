@php
    use Illuminate\Support\Str;

    $id ??= 'quill-'.Str::uuid();
    $rows = max(1, (int) $rows);
    $invalid = filter_var($invalid, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $isRequired = $attributes->has('required');

    $enabledFormats = match (true) {
        is_array($formats) => array_values($formats),
        default => quillDefaultFormats(),
    };

    $wireModelProperty = null;

    if ($livewire) {
        foreach (['wire:model.live', 'wire:model.blur', 'wire:model'] as $directive) {
            if ($attributes->has($directive)) {
                $wireModelProperty = $attributes->get($directive);
                break;
            }
        }
    }

    $inputName = $name ?? $attributes->get('name');

    $quillConfig = [
        'toolbar' => quillToolbar($enabledFormats),
        'formats' => quillAllowedFormats($enabledFormats),
        'placeholder' => $placeholder,
        'rows' => $rows,
        'disabled' => $disabled,
        'livewire' => (bool) $livewire,
        'modelProperty' => $wireModelProperty,
        'value' => $livewire ? null : ($value ?? ''),
    ];

    $rootAttributes = $attributes
        ->except([
            'class',
            'disabled',
            'formats',
            'id',
            'invalid',
            'name',
            'placeholder',
            'required',
            'rows',
            'value',
            'wire:key',
            'wire:model',
            'wire:model.live',
            'wire:model.blur',
        ])
        ->class([
            'quill-wrapper w-full',
            'quill-wrapper--invalid' => $invalid,
            'quill-wrapper--disabled' => $disabled,
        ]);

    $textareaAttributes = $attributes->only(['required']);
@endphp

<div
    @if ($livewire) wire:ignore @endif
    @if ($livewire)
        x-data="quillEditor(@js($quillConfig), $wire)"
    @else
        x-data="quillEditor(@js($quillConfig))"
    @endif
    {{ $rootAttributes }}
>
    <div x-ref="editor" class="quill-editor" style="min-height: {{ $rows * 1.5 }}rem;"></div>

    <textarea
        x-ref="input"
        id="{{ $id }}"
        class="hidden"
        @if (! $livewire && filled($inputName)) name="{{ $inputName }}" @endif
        @if ($disabled) disabled @endif
        @if ($isRequired) required @endif
        {{ $textareaAttributes }}
    >@unless ($livewire){{ $value ?? '' }}@endunless</textarea>
</div>
