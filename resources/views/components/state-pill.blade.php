@props([
    'state',
    'class' => '',
])

<div {{ $attributes->class([
    $state->getUiClasses(),
    $class,
]) }}>
    <span>{{ $state->label() }}</span>
</div>
