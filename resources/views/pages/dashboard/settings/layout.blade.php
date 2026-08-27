<div class="space-y-6">
    <div class="border-b border-zinc-200">
        <flux:navbar class="gap-3" aria-label="{{ __('الإعدادات') }}">
            <flux:navbar.item
                :href="route('dashboard.settings.edit')"
                :current="request()->routeIs('dashboard.settings.edit')"
                icon="cog"
                wire:navigate
            >
                {{ __('الإعدادات العامة') }}
            </flux:navbar.item>

            <flux:navbar.item
                :href="route('dashboard.settings.social.edit')"
                :current="request()->routeIs('dashboard.settings.social.*')"
                icon="share"
                wire:navigate
            >
                {{ __('وسائل التواصل') }}
            </flux:navbar.item>
        </flux:navbar>
    </div>

    {{ $slot }}
</div>
