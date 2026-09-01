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

if (! function_exists('quillDefaultFormats')) {
    /**
     * Get the default Quill toolbar format keys (excludes image and video).
     *
     * @return list<string>
     */
    function quillDefaultFormats(): array
    {
        return [
            'header',
            'bold',
            'italic',
            'underline',
            'strike',
            'blockquote',
            'code-block',
            'direction',
            'align',
            'list',
            'indent',
            'link',
            'clean',
        ];
    }
}

if (! function_exists('quillToolbar')) {
    /**
     * Build a Quill toolbar configuration from enabled format keys.
     *
     * @param  list<string>  $formats
     * @return list<array<int, mixed>>
     */
    function quillToolbar(array $formats): array
    {
        $toolbar = [];

        if (in_array('header', $formats, true)) {
            $toolbar[] = [['header' => [1, 2, 3, false]]];
        }

        $inlineFormats = array_values(array_intersect(['bold', 'italic', 'underline', 'strike'], $formats));

        if ($inlineFormats !== []) {
            $toolbar[] = $inlineFormats;
        }

        $blockFormats = array_values(array_intersect(['blockquote', 'code-block'], $formats));

        if ($blockFormats !== []) {
            $toolbar[] = $blockFormats;
        }

        if (in_array('list', $formats, true)) {
            $toolbar[] = [['list' => 'ordered'], ['list' => 'bullet']];
        }

        if (in_array('indent', $formats, true)) {
            $toolbar[] = [['indent' => '-1'], ['indent' => '+1']];
        }

        if (in_array('direction', $formats, true)) {
            $toolbar[] = [['direction' => 'rtl']];
        }

        if (in_array('align', $formats, true)) {
            $toolbar[] = [['align' => []]];
        }

        if (in_array('link', $formats, true)) {
            $toolbar[] = ['link'];
        }

        if (in_array('clean', $formats, true)) {
            $toolbar[] = ['clean'];
        }

        return $toolbar;
    }
}

if (! function_exists('quillAllowedFormats')) {
    /**
     * Build the Quill formats whitelist from enabled format keys.
     *
     * @param  list<string>  $formats
     * @return list<string>
     */
    function quillAllowedFormats(array $formats): array
    {
        $allowed = [];

        foreach ($formats as $format) {
            match ($format) {
                'header' => $allowed[] = 'header',
                'bold' => $allowed[] = 'bold',
                'italic' => $allowed[] = 'italic',
                'underline' => $allowed[] = 'underline',
                'strike' => $allowed[] = 'strike',
                'blockquote' => $allowed[] = 'blockquote',
                'code-block' => $allowed[] = 'code-block',
                'direction' => $allowed[] = 'direction',
                'align' => $allowed[] = 'align',
                'list' => array_push($allowed, 'list'),
                'indent' => array_push($allowed, 'indent'),
                'link' => $allowed[] = 'link',
                default => null,
            };
        }

        return array_values(array_unique($allowed));
    }
}
