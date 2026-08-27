<x-layouts::dashboard :title="__('إضافة تصنيف جديد')">
    @php
        $breadcrumbs = [
            ['name' => __('التصنيفات'), 'url' => route('dashboard.categories.index')],
            ['name' => __('إضافة تصنيف جديد'), 'active' => true],
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
            <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data" class="non-wire">
                @csrf

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('إضافة تصنيف جديد') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('name')]) for="name" badge="*" required>{{ __('الاسم') }}</flux:label>
                                <flux:input type="text" id="name" name="name" :value="old('name')" autocomplete="off" required />
                                <flux:error name="name" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('slug')]) for="slug" badge="*" required>{{ __('الرابط') }}</flux:label>
                                <flux:input type="text" id="slug" name="slug" :value="old('slug')" autocomplete="off" lang="en" required />
                                <flux:error name="slug" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('sort_order')]) for="sort_order" badge="*" required>{{ __('ترتيب العرض') }}</flux:label>
                                <flux:input type="number" id="sort_order" name="sort_order" class="font-mono" :value="old('sort_order')" min="1" step="1" lang="en" required />
                                <flux:error name="sort_order" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('image')]) for="image">{{ __('الصورة') }}</flux:label>
                                <flux:input type="file" id="image" name="image" class="p-1 border border-zinc-200 rounded-lg" size="sm" accept="image/jpeg,image/png,image/webp" />
                                <flux:error name="image" />
                            </flux:field>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('description')]) for="description">{{ __('الوصف') }}</flux:label>
                                <flux:textarea id="description" name="description" rows="5">{{ old('description') }}</flux:textarea>
                                <flux:error name="description" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('technical_description')]) for="technical_description">{{ __('الوصف التقني') }}</flux:label>
                                <flux:textarea id="technical_description" name="technical_description" rows="5">{{ old('technical_description') }}</flux:textarea>
                                <flux:error name="technical_description" />
                            </flux:field>
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.categories.index')" size="sm" icon="arrow-uturn-left" wire:navigate>{{ __('إلغاء الأمر') }}</flux:button>
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('إضـافـة') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
