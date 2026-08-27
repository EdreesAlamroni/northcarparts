<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard.index') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="gap-1.5">
                <flux:sidebar.group expandable heading="العمليات الأساسية">
                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard.index')"
                        :current="request()->routeIs('dashboard.index')"
                        wire:navigate
                    >
                        {{ __('الرئيسية') }}
                    </flux:sidebar.item>

                    @canany(['viewAny'], \App\Models\Category::class)
                        <flux:sidebar.item
                            icon="squares-2x2"
                            :href="route('dashboard.categories.index')"
                            :current="request()->routeIs('dashboard.categories.*')"
                            wire:navigate
                        >
                            {{ __('التصنيفات') }}
                        </flux:sidebar.item>
                    @endcanany

                    @canany(['viewAny'], \App\Models\Product::class)
                        <flux:sidebar.item
                            icon="cube"
                            :href="route('dashboard.products.index')"
                            :current="request()->routeIs('dashboard.products.*')"
                            wire:navigate
                        >
                            {{ __('المنتجات') }}
                        </flux:sidebar.item>
                    @endcanany

                    @canany(['viewAny'], \App\Models\News::class)
                        <flux:sidebar.item
                            icon="newspaper"
                            :href="route('dashboard.news.index')"
                            :current="request()->routeIs('dashboard.news.*')"
                            wire:navigate
                        >
                            {{ __('الأخبار') }}
                        </flux:sidebar.item>
                    @endcanany
                </flux:sidebar.group>

                <flux:sidebar.group expandable heading="الإعدادات">
                    @canany(['viewAny'], \App\Models\Manufacturer::class)
                        <flux:sidebar.item
                            icon="building-office"
                            :href="route('dashboard.manufacturers.index')"
                            :current="request()->routeIs('dashboard.manufacturers.*')"
                            wire:navigate
                        >
                            {{ __('الشركات المصنعة') }}
                        </flux:sidebar.item>
                    @endcanany

                    @canany(['viewAny'], \App\Models\Specification::class)
                        <flux:sidebar.item
                            icon="clipboard-document-list"
                            :href="route('dashboard.specifications.index')"
                            :current="request()->routeIs('dashboard.specifications.*')"
                            wire:navigate
                        >
                            {{ __('خصائص المنتجات') }}
                        </flux:sidebar.item>
                    @endcanany

                    @canany(['viewAny'], \App\Models\User::class)
                        <flux:sidebar.item
                            icon="user-group"
                            :href="route('dashboard.users.index')"
                            :current="request()->routeIs('dashboard.users.*')"
                            wire:navigate
                        >
                            {{ __('المستخدمين') }}
                        </flux:sidebar.item>
                    @endcanany

                    @can('view', \App\Authorization\Settings::class)
                        <flux:sidebar.item
                            icon="cog-6-tooth"
                            :href="route('dashboard.settings.edit')"
                            :current="request()->routeIs('dashboard.settings.*')"
                            wire:navigate
                        >
                            {{ __('الإعدادات') }}
                        </flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth('web')->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth('web')->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth('web')->user()->name"
                                    :initials="auth('web')->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth('web')->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth('web')->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('dashboard.account-settings.profile.edit')" icon="cog" wire:navigate>
                            {{ __('إعدادات الحساب') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('تسجيل الخروج') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <x-toast-stack />

        @fluxScripts
    </body>
</html>
