import './navigate-form';
import './grouped-roles-fieldset';
import './flash-toast';
import { lazyAlpineData } from './lazy-alpine';

lazyAlpineData('quillEditor', async () => (await import('./quill')).quillEditor);
lazyAlpineData('filepondInput', async () => (await import('./filepond')).filepondInput);
lazyAlpineData('dashboardChart', async () => (await import('./charts')).dashboardChart);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.non-wire').forEach(form => {
        form.addEventListener('submit', (event) => {
            const button = event.target.querySelector('button[type=submit]');

            if (!button) {
                return;
            }

            button.setAttribute('disabled', true);
            button.setAttribute('type', 'button');
        });
    });
});


document.addEventListener('alpine:init', () => {
    Alpine.data('sanitizePhoneNumberInput', () => ({
        init() {
            this.sanitize()

            this.$el.addEventListener('input', () => this.sanitize())
        },

        sanitize() {
            this.$el.value = this.$el.value.replace(/[^0-9]/g, '');

            this.toggleFont();
        },

        toggleFont() {
            if (this.$el.value === '') {
                this.$el.classList.remove('font-mono')
            } else {
                this.$el.classList.add('font-mono')
            }
        }
    }))


    Alpine.data('toggleDateFontOnFocus', () => ({
        init() {
            this.toggleFont()

            this.$el.addEventListener('input', () => this.toggleFont())
            this.$el.addEventListener('focus', () => this.$el.type = 'date')
            this.$el.addEventListener('blur', () => this.$el.type = 'text')
        },

        toggleFont() {
            if (this.$el.value === '') {
                this.$el.classList.remove('font-mono')
            } else {
                this.$el.classList.add('font-mono')
            }
        }
    }))

    Alpine.data('autoGenerateSlug', () => ({
        slugManuallyEdited: false,

        init() {
            const { code: codeInput, slug: slugInput } = this.$refs

            if (! codeInput || ! slugInput) {
                return
            }

            if (slugInput.value !== '' && slugInput.value !== this.slugify(codeInput.value)) {
                this.slugManuallyEdited = true
            }

            codeInput.addEventListener('input', () => {
                if (! this.slugManuallyEdited) {
                    slugInput.value = this.slugify(codeInput.value)
                }
            })

            slugInput.addEventListener('input', () => {
                this.slugManuallyEdited = true
            })
        },

        slugify(value) {
            return value
                .toString()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
        },
    }))
});
