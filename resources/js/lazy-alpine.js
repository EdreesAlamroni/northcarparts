export function lazyAlpineData(name, loadFactory) {
    document.addEventListener('alpine:init', () => {
        Alpine.data(name, (...args) => ({
            async init() {
                const factory = await loadFactory();
                const impl = factory(...args);

                for (const [key, value] of Object.entries(impl)) {
                    if (key === 'init') {
                        continue;
                    }

                    this[key] = typeof value === 'function' ? value.bind(this) : value;
                }

                if (typeof impl.init === 'function') {
                    await impl.init.call(this);
                }

                this._lazyImpl = impl;
            },

            destroy() {
                this._lazyImpl?.destroy?.call(this);
            },
        }));
    });
}
