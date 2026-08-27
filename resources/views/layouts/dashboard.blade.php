<x-layouts::dashboard.sidebar :title="$title ?? null">
    <section class="relative border-b px-6 min-h-16 py-4 flex items-center max-lg:hidden">
        @isset($breadcrumbs)
            <div>
                {{ $breadcrumbs }}
            </div>
        @endisset
    </section>

    <flux:main class="lg:px-6 px-6 space-y-6">
        @isset($mobileBreadcrumbs)
            <section class="lg:hidden">
                {{ $mobileBreadcrumbs }}
            </section>
        @endisset

        {{ $slot }}
    </flux:main>
</x-layouts::dashboard.sidebar>
