@props([
    'formats' => null,
    'placeholder' => null,
    'rows' => 5,
    'invalid' => false,
    'disabled' => false,
    'name' => null,
    'id' => null,
    'value' => null,
])

@include('components.partials.quill-base', ['livewire' => false])
