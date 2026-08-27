<x-layouts::dashboard :title="__('الرئيسية')">
    @php
        $breadcrumbs = [
            ['name' => __('الرئيسية'), 'active' => true],
        ];
    @endphp

    <x-slot:breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:breadcrumbs>

    <x-slot:mobile-breadcrumbs>
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    </x-slot:mobile-breadcrumbs>

    <div class="space-y-10 md:space-y-8">
        <section class="space-y-4">
            <x-dashboard.section-heading tone="blue" :title="__('نظرة عامة على المنتجات')" />
            <livewire:pages::dashboard.home.product-overview />
        </section>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="space-y-4">
                <x-dashboard.section-heading tone="blue" :title="__('التصنيفات والشركات')" />
                <livewire:pages::dashboard.home.catalog-overview />
            </section>

            <section class="space-y-4">
                <x-dashboard.section-heading tone="blue" :title="__('الأخبار')" />
                <livewire:pages::dashboard.home.news-overview defer />
            </section>
        </div>

        <section>
            <livewire:pages::dashboard.home.products-by-category-chart defer />
        </section>

        <section class="space-y-4">
            <x-dashboard.section-heading tone="blue" :title="__('أبرز الشركات المصنعة')" />
            <livewire:pages::dashboard.home.top-manufacturers defer />
        </section>
    </div>
</x-layouts::dashboard>
