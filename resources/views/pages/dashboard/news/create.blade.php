<x-layouts::dashboard :title="__('إضافة خبر جديد')">
    @php
        $breadcrumbs = [
            ['name' => __('الأخبار'), 'url' => route('dashboard.news.index')],
            ['name' => __('إضافة خبر جديد'), 'active' => true],
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
            <form action="{{ route('dashboard.news.store') }}" method="POST" enctype="multipart/form-data" class="non-wire">
                @csrf

                <x-card>
                    <x-slot:heading>
                        <x-slot:title>
                            <flux:heading>{{ __('إضافة خبر جديد') }}</flux:heading>
                        </x-slot:title>
                        <x-slot:description>
                            <x-required-fields-note />
                        </x-slot:description>
                    </x-slot:heading>

                    <x-slot:slot class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('title')]) for="title" badge="*" required>{{ __('العنوان') }}</flux:label>
                                <flux:input type="text" id="title" name="title" :value="old('title')" autocomplete="off" required />
                                <flux:error name="title" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('slug')]) for="slug" badge="*" required>{{ __('الرابط') }}</flux:label>
                                <flux:input type="text" id="slug" name="slug" :value="old('slug')" autocomplete="off" lang="en" required />
                                <flux:error name="slug" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('published_at')]) for="published_at">{{ __('تاريخ النشر') }}</flux:label>
                                <flux:input type="date" id="published_at" name="published_at" :value="old('published_at', now()->toDateString())" class="font-mono" />
                                <flux:error name="published_at" />
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
                                <flux:label @class(['text-red-600' => $errors->has('content')]) for="content" badge="*" required>{{ __('المحتوى') }}</flux:label>
                                <flux:textarea id="content" name="content" rows="10" required>{{ old('content') }}</flux:textarea>
                                <flux:error name="content" />
                            </flux:field>

                            <flux:field>
                                <flux:label @class(['text-red-600' => $errors->has('image')]) for="image">{{ __('الصورة') }}</flux:label>
                                <flux:input type="file" id="image" name="image" class="p-1 border border-zinc-200 rounded-lg" size="sm" accept="image/jpeg,image/png,image/webp" />
                                <flux:error name="image" />
                            </flux:field>
                        </div>
                    </x-slot:slot>

                    <x-slot:footer class="flex items-center justify-end gap-x-3">
                        <flux:button :href="route('dashboard.news.index')" size="sm" icon="arrow-uturn-left" wire:navigate>{{ __('إلغاء الأمر') }}</flux:button>
                        <flux:button type="submit" variant="primary" size="sm" icon="check-circle" iconVariant="outline">{{ __('إضـافـة') }}</flux:button>
                    </x-slot:footer>
                </x-card>
            </form>
        </section>
    </div>
</x-layouts::dashboard>
