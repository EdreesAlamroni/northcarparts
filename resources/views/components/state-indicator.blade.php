@props([
    'state',
    'class' => '',
    'childClassName' => '',
])

<div {{ $attributes->class([
    'inline-flex items-center justify-center w-4 h-4 mt-[2px] p-1 rounded-none',
    "state-indicator--{$state}",
    $class,
]) }}>
    <div @class([
        'block w-[0.35rem] h-[0.35rem] rounded-none bg-current',
        $childClassName,
    ])></div>
</div>
