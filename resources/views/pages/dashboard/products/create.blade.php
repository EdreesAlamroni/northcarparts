<x-layouts::dashboard :title="__('إضافة منتج جديد')">
    @php
        $breadcrumbs = [
            ['name' => __('المنتجات'), 'url' => route('dashboard.products.index')],
            ['name' => __('إضافة منتج جديد'), 'active' => true],
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
            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="non-wire" x-data="autoGenerateSlug()">
                @csrf

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('إضافة منتج جديد') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('code')]) for="code" badge="*" required>{{ __('كود الفلتر') }}</flux:label>
                                <flux:input type="text" id="code" name="code" x-ref="code" :value="old('code')" autocomplete="off" lang="en" required />
                                <flux:error name="code" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('slug')]) for="slug" badge="*" required>{{ __('الرابط (Slug)') }}</flux:label>
                                <flux:input type="text" id="slug" name="slug" x-ref="slug" :value="old('slug')" class="font-mono" autocomplete="off" lang="en" required />
                                <flux:error name="slug" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('category_id')]) for="category_id" badge="*" required>{{ __('التصنيف') }}</flux:label>
                                <flux:select name="category_id" id="category_id" :placeholder="__('اختر التصنيف')">
                                    @foreach ($categories as $category)
                                        <flux:select.option :value="$category->id" :selected="old('category_id') === $category->id">
                                            {{ $category->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="category_id" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('oem_manufacturer_id')]) for="oem_manufacturer_id" badge="*" required>{{ __('الشركة المصنعة OEM') }}</flux:label>
                                <flux:select name="oem_manufacturer_id" id="oem_manufacturer_id" :placeholder="__('اختر الشركة المصنعة OEM')">
                                    @foreach ($manufacturers as $manufacturer)
                                        <flux:select.option :value="$manufacturer->id" :selected="old('oem_manufacturer_id') === $manufacturer->id">
                                            {{ $manufacturer->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="oem_manufacturer_id" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('oem_number')]) for="oem_number" badge="*" required>{{ __('رقم OEM') }}</flux:label>
                                <flux:input type="text" id="oem_number" name="oem_number" :value="old('oem_number')" autocomplete="off" lang="en" required />
                                <flux:error name="oem_number" />
                            </flux:field>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('image')]) for="image">{{ __('الصورة') }}</flux:label>
                                <flux:input type="file" id="image" name="image" class="p-1 border border-zinc-200 rounded-lg" size="sm" accept="image/jpeg,image/png,image/webp" />
                                <flux:error name="image" />
                            </flux:field>
                        </div>

                        <x-grouped-specifications-fieldset :grouped-specifications="$groupedSpecifications" />
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.products.index')" size="sm" icon="arrow-uturn-left" wire:navigate>{{ __('إلغاء الأمر') }}</flux:button>
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('إضـافـة') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
