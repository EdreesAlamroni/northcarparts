<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50">
            <flux:sidebar.toggle class="lg:hidden me-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard.index') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard.index')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('الرئيسية') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard.index') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-me-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="layout-grid" :href="route('dashboard.index')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('الرئيسية') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:spacer />

        </flux:sidebar>

        {{ $slot }}

        <x-toast-stack />

        @fluxScripts
    </body>
</html>
