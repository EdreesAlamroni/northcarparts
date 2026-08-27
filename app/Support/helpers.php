<?php

use Flux\Flux;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\Livewire;

if (! function_exists('toast')) {
    /**
     * Display a translated toast notification with optional replacements.
     */
    function toast(string $variant, string $key, array $replacements = []): void
    {
        $message = Lang::get(sprintf('alerts.messages.%s', $key), $replacements);

        if (Livewire::current() instanceof Component) {
            Flux::toast(variant: $variant, text: $message);

            return;
        }

        session()->flash('toast', [
            'variant' => $variant,
            'text' => $message,
        ]);
    }
}

if (! function_exists('toast_success')) {
    /**
     * Display a translated success toast notification with optional replacements.
     */
    function toast_success(string $key, array $replacements = []): void
    {
        toast('success', $key, $replacements);
    }
}

if (! function_exists('toast_error')) {
    /**
     * Display a translated error toast notification with optional replacements.
     */
    function toast_error(string $key, array $replacements = []): void
    {
        toast('danger', $key, $replacements);
    }
}

if (! function_exists('toast_warning')) {
    /**
     * Display a translated warning toast notification with optional replacements.
     */
    function toast_warning(string $key, array $replacements = []): void
    {
        toast('warning', $key, $replacements);
    }
}
