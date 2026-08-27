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
            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="non-wire">
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
                                <flux:label @class(['text-red-600' => $errors->has('code')]) for="code" badge="*" required>{{ __('الكود') }}</flux:label>
                                <flux:input type="text" id="code" name="code" :value="old('code')" autocomplete="off" lang="en" required />
                                <flux:error name="code" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('slug')]) for="slug" badge="*" required>{{ __('الرابط') }}</flux:label>
                                <flux:input type="text" id="slug" name="slug" :value="old('slug')" autocomplete="off" lang="en" required />
                                <flux:error name="slug" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('name')]) for="name" badge="*" required>{{ __('الاسم') }}</flux:label>
                                <flux:input type="text" id="name" name="name" :value="old('name')" autocomplete="off" required />
                                <flux:error name="name" />
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

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('filter_type')]) for="filter_type" badge="*" required>{{ __('نوع الفلتر') }}</flux:label>
                                <flux:select name="filter_type" id="filter_type" :placeholder="__('اختر نوع الفلتر')">
                                    @foreach ($filterTypes as $type)
                                        <flux:select.option :value="$type->id" :selected="old('filter_type') === $type->id">
                                            {{ $type->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="filter_type" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('qr_code_redirect_url')]) for="qr_code_redirect_url" badge="*" required>{{ __('رابط الـ QR Code') }}</flux:label>
                                <flux:input type="url" id="qr_code_redirect_url" name="qr_code_redirect_url" :value="old('qr_code_redirect_url')" autocomplete="off" lang="en" required />
                                <flux:error name="qr_code_redirect_url" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('sort_order')]) for="sort_order" badge="*" required>{{ __('ترتيب العرض') }}</flux:label>
                                <flux:input type="number" id="sort_order" name="sort_order" class="font-mono" :value="old('sort_order')" min="1" step="1" lang="en" required />
                                <flux:error name="sort_order" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('state')]) for="state" badge="*" required>{{ __('الحالة') }}</flux:label>
                                <flux:select name="state" id="state">
                                    @foreach ($states as $state)
                                        <flux:select.option :value="$state->id" :selected="old('state') === $state->id">
                                            {{ $state->action ?? $state->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="state" />
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
