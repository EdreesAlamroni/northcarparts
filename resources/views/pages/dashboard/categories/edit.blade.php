<x-layouts::dashboard :title="__('تعديل بيانات التصنيف')">
    @php
        $breadcrumbs = [
            ['name' => __('التصنيفات'), 'url' => route('dashboard.categories.index')],
            ['name' => __('عرض بيانات التصنيف'), 'url' => route('dashboard.categories.show', $category)],
            ['name' => __('تعديل بيانات التصنيف'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-6">
        <x-validation-errors :errors="$errors" />

        <section>
            <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" class="non-wire">
                @csrf
                @method('PUT')

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('تعديل بيانات التصنيف') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('name')]) for="name" badge="*" required>{{ __('الاسم') }}</flux:label>
                                <flux:input type="text" id="name" name="name" :value="old('name', $category->name)" autocomplete="off" required />
                                <flux:error name="name" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('slug')]) for="slug" badge="*" required>{{ __('الرابط') }}</flux:label>
                                <flux:input type="text" id="slug" name="slug" :value="old('slug', $category->slug)" autocomplete="off" lang="en" required />
                                <flux:error name="slug" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('sort_order')]) for="sort_order" badge="*" required>{{ __('ترتيب العرض') }}</flux:label>
                                <flux:input type="number" id="sort_order" name="sort_order" :value="old('sort_order', $category->sort_order)" min="0" required />
                                <flux:error name="sort_order" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('state')]) for="state" badge="*" required>{{ __('الحالة') }}</flux:label>
                                <flux:select name="state" id="state">
                                    @foreach ($states as $state)
                                        <flux:select.option
                                            :value="$state->id"
                                            :selected="old('state', $category->state->value()) === $state->id"
                                        >
                                            {{ $state->action ?? $state->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="state" />
                            </flux:field>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('description')]) for="description">{{ __('الوصف') }}</flux:label>
                                <flux:textarea id="description" name="description" rows="5">{{ old('description', $category->description) }}</flux:textarea>
                                <flux:error name="description" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('technical_description')]) for="technical_description">{{ __('الوصف التقني') }}</flux:label>
                                <flux:textarea id="technical_description" name="technical_description" rows="5">{{ old('technical_description', $category->technical_description) }}</flux:textarea>
                                <flux:error name="technical_description" />
                            </flux:field>
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.categories.show', $category)" size="sm" icon="arrow-uturn-left" wire:navigate>{{ __('إلغاء الأمر') }}</flux:button>
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('تـحـديـث') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
