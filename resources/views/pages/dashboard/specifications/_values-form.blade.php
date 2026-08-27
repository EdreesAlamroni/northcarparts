<div class="space-y-4 border-t border-zinc-200 pt-6" x-data="{
    values: @js($values),

    add() {
        this.values.push('');
    },

    remove(index) {
        if (this.values.length === 1) {
            return;
        }

        this.values.splice(index, 1);
    },

    idFieldName(index) {
        return 'specification-value-' + index;
    },

    nameFieldName(index) {
        return 'values[' + index + ']';
    },
}">
    <div class="flex items-center justify-between gap-3">
        <flux:heading size="sm">{{ __('قيم الخاصية') }}</flux:heading>
        <flux:button type="button" size="sm" icon="plus" x-on:click="add()">{{ __('إضافة قيمة الخاصية') }}</flux:button>
    </div>

    <template x-for="(value, index) in values" :key="index">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_auto] md:items-end md:gap-6">
            <flux:field>
                <flux:label x-bind:for="idFieldName(index)">{{ __('قيمة الخاصية') }}</flux:label>
                <flux:input type="text" x-bind:id="idFieldName(index)" x-bind:name="nameFieldName(index)" x-model="values[index]" autocomplete="off" />
            </flux:field>

            <flux:button
                type="button"
                variant="danger"
                size="sm"
                icon="trash"
                class="justify-self-end md:justify-self-auto mb-1"
                aria-label="{{ __('حذف') }}"
                x-bind:disabled="values.length === 1"
                x-on:click="remove(index)"
            />
        </div>
    </template>
</div>
