<x-layouts::dashboard :title="__('تعديل بيانات الشركة المصنعة')">
    @php
        $breadcrumbs = [
            ['name' => __('الشركات المصنعة'), 'url' => route('dashboard.manufacturers.index')],
            ['name' => __('عرض بيانات الشركة المصنعة'), 'url' => route('dashboard.manufacturers.show', $manufacturer)],
            ['name' => __('تعديل بيانات الشركة المصنعة'), 'active' => true],
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
            <form action="{{ route('dashboard.manufacturers.update', $manufacturer) }}" method="POST" class="non-wire">
                @csrf
                @method('PUT')

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('تعديل بيانات الشركة المصنعة') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('name')]) for="name" badge="*" required>{{ __('الاسم') }}</flux:label>
                                <flux:input type="text" id="name" name="name" :value="old('name', $manufacturer->name)" autocomplete="off" required />
                                <flux:error name="name" />
                            </flux:field>
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.manufacturers.show', $manufacturer)" size="sm" icon="arrow-uturn-left" wire:navigate>{{ __('إلغاء الأمر') }}</flux:button>
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('تـحـديـث') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
