@props([
    'accept' => null,
    'multiple' => false,
    'allowImagePreview' => true,
    'maxFileSize' => null,
    'name' => null,
    'id' => null,
])

@include('components.partials.filepond-base', ['livewire' => true])
