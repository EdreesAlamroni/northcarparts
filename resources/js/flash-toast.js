document.addEventListener('alpine:init', function () {
    Alpine.data('flashToast', function (config) {
        return {
            toast: config.toast || null,

            init: function () {
                if (!this.toast || typeof window.Flux?.toast !== 'function') {
                    return;
                }

                window.Flux.toast(this.toast);
            },
        };
    });
});
