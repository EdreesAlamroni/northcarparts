<?php

use Flux\Flux;
use Illuminate\Support\Collection;
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

if (! function_exists('allowedImageMimetypes')) {
    /**
     * Get the MIME types allowed for image uploads.
     *
     * @return Collection<int, string>
     */
    function allowedImageMimetypes(): Collection
    {
        return collect(['image/jpeg', 'image/png', 'image/webp']);
    }
}

if (! function_exists('allowedImageMaxFileSize')) {
    /**
     * Get the maximum allowed image upload size for FilePond client-side validation.
     */
    function allowedImageMaxFileSize(): string
    {
        return '10MB';
    }
}

if (! function_exists('navigate_preserving_scroll')) {
    /**
     * Navigate to the given URL without resetting scroll position.
     *
     * Uses Livewire's SPA navigation when called from a Livewire component action.
     */
    function navigate_preserving_scroll(string $url): void
    {
        $component = Livewire::current();

        if ($component instanceof Component) {
            $component->js('Alpine.navigate('.json_encode($url).', { preserveScroll: true })');

            return;
        }

        redirect($url);
    }
}
