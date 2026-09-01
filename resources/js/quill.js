import Quill, { Delta } from 'quill';
import { ClassAttributor, Scope } from 'parchment';

const emptyEditorHtml = '<p><br></p>';

const stripMediaMatcher = () => new Delta();

const isBlankHtml = (html) => {
    if (! html) {
        return true;
    }

    const stripped = html.replace(/<[^>]*>/g, '').trim();

    return stripped === '' || html === emptyEditorHtml;
};

// Extend Quill's direction format to support both rtl and ltr so the
// toolbar toggle can switch explicitly between the two directions.
const DirectionClass = new ClassAttributor('direction', 'ql-direction', {
    scope: Scope.BLOCK,
    whitelist: ['rtl', 'ltr'],
});

Quill.register(DirectionClass, true);

export function quillEditor(config, wire = null) {
    return {
        quill: null,

        init() {
            const initialValue = this.resolveInitialValue(config, wire);

            this.quill = new Quill(this.$refs.editor, {
                theme: 'snow',
                placeholder: config.placeholder ?? '',
                modules: {
                    toolbar: {
                        container: config.toolbar,
                        handlers: {
                            link: this.handleLink.bind(this),
                            direction: this.toggleDirection,
                        },
                    },
                    clipboard: {
                        matchers: [
                            ['IMG', stripMediaMatcher],
                            ['VIDEO', stripMediaMatcher],
                            ['IFRAME', stripMediaMatcher],
                        ],
                    },
                },
                formats: config.formats,
            });

            if (initialValue) {
                this.quill.root.innerHTML = initialValue;
            }

            this.applyDefaultDirection(initialValue);

            this.syncValue(this.quill.root.innerHTML);

            this.quill.on('text-change', () => {
                this.syncValue(this.normalizeHtml(this.quill.root.innerHTML));
            });

            if (config.disabled) {
                this.quill.enable(false);
            }
        },

        resolveInitialValue(config, wire) {
            if (config.livewire && wire && config.modelProperty) {
                return wire.get(config.modelProperty) ?? '';
            }

            return config.value ?? this.$refs.input.value ?? '';
        },

        applyDefaultDirection(value) {
            if (! config.formats.includes('direction')) {
                return;
            }

            if (! isBlankHtml(value)) {
                return;
            }

            this.quill.formatLine(0, 1, { direction: 'rtl' }, 'silent');
        },

        toggleDirection(value) {
            const range = this.quill.getSelection();

            if (! range) {
                return;
            }

            const current = this.quill.getFormat(range.index, range.length)?.direction;
            const next = current === 'ltr' ? 'rtl' : 'ltr';

            this.quill.format('direction', next);
        },

        handleLink() {
            const range = this.quill.getSelection();

            if (! range) {
                return;
            }

            const existing = this.quill.getFormat(range.index, range.length)?.link ?? '';
            const url = window.prompt(this.linkPromptLabel(), existing);

            if (url === null) {
                return;
            }

            if (url === '') {
                this.quill.format('link', false);

                return;
            }

            this.quill.format('link', this.normalizeUrl(url));
        },

        linkPromptLabel() {
            return document.documentElement.lang === 'ar'
                ? 'أدخل الرابط:'
                : 'Enter link:';
        },

        normalizeUrl(url) {
            const trimmed = url.trim();

            if (trimmed === '') {
                return '';
            }

            if (/^(https?:|mailto:|tel:|#)/i.test(trimmed)) {
                return trimmed;
            }

            return `https://${trimmed}`;
        },

        normalizeHtml(html) {
            if (! html || html === emptyEditorHtml) {
                return '';
            }

            const text = this.$refs.editor.textContent?.trim() ?? '';

            return text === '' ? '' : html;
        },

        syncValue(html) {
            const normalizedHtml = this.normalizeHtml(html);

            this.$refs.input.value = normalizedHtml;

            if (config.livewire && wire && config.modelProperty) {
                wire.set(config.modelProperty, normalizedHtml);

                return;
            }

            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        },

        destroy() {
            if (! this.quill) {
                return;
            }

            this.quill.off('text-change');

            const toolbar = this.$refs.editor.previousElementSibling;

            if (toolbar?.classList.contains('ql-toolbar')) {
                toolbar.remove();
            }

            this.$refs.editor.innerHTML = '';
            this.quill = null;
        },
    };
}
