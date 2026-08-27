@props([
    'action' => '#',
    'title' => __('حـذف'),
])

@php
    $isDisabled = $action === '#';
    $modalName = 'confirm-delete-'.md5($action);
@endphp

<div {{ $attributes }}>
    <flux:modal.trigger :name="$modalName">
        <flux:button type="button" variant="danger" size="sm" icon="trash" class="[&:focus]:!ring-red-500">
            {{ $title }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal :name="$modalName" class="sm:min-w-lg min-w-sm">
        <form
            action="{{ $action }}"
            method="POST"
            class="space-y-6 non-wire"
            @if ($isDisabled) onsubmit="event.preventDefault();" @endif
        >
            @csrf
            @method('DELETE')

            <div>
                <flux:heading size="lg">{{ __('هل أنت متأكد من الحذف ؟') }}</flux:heading>

                <flux:text class="mt-2 text-sm">
                    {{ __('لن تتمكن من التراجع عن هذا اﻹجراء .') }}
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button type="button" size="sm">{{ __('إلغاء الأمر') }}</flux:button>
                </flux:modal.close>

                <flux:button
                    type="{{ $isDisabled ? 'button' : 'submit' }}"
                    variant="danger"
                    size="sm"
                    icon="check-circle"
                    iconVariant="outline"
                    class="[&:focus]:!ring-red-500"
                    x-bind:disabled="{{ $isDisabled ? 'true' : 'false' }}"
                >
                    {{ __('تـأكـيـد') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
