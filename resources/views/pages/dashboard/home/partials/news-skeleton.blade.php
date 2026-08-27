<div class="grid auto-rows-min gap-4 sm:gap-5 md:grid-cols-2">
    @foreach (range(1, 2) as $index)
        <div @class(['dashboard-skeleton dashboard-skeleton--' . ($index === 1 ? 'sky' : 'pink'), 'dashboard-skeleton-card p-5 lg:p-6'])>
            <div class="space-y-3">
                <div class="dashboard-skeleton__title h-7 w-7 animate-pulse rounded-lg"></div>
                <div class="dashboard-skeleton__subtitle h-4 w-32 animate-pulse rounded"></div>
                <div class="dashboard-skeleton__body h-12 animate-pulse rounded-lg"></div>
            </div>
        </div>
    @endforeach
</div>
