function isGetForm(form) {
    return form.method.toLowerCase() === 'get';
}

function shouldNavigateForm(form) {
    return form instanceof HTMLFormElement
        && form.hasAttribute('wire:navigate')
        && isGetForm(form)
        && typeof window.Livewire?.navigate === 'function';
}

function destinationFromForm(form) {
    const url = new URL(form.action);
    const params = new URLSearchParams(new FormData(form));

    for (const [key, value] of [...params.entries()]) {
        if (value.trim() === '') {
            params.delete(key);
        }
    }

    url.search = params.toString();

    return url.toString();
}

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!shouldNavigateForm(form)) {
        return;
    }

    event.preventDefault();

    window.Livewire.navigate(destinationFromForm(form), {
        preserveScroll: form.hasAttribute('wire:navigate.preserve-scroll'),
    });
});
