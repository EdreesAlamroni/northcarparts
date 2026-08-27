import './navigate-form';
import './grouped-roles-fieldset';
import './grouped-specifications-fieldset';
import './flash-toast';
import './charts';

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
});
