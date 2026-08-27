<x-layouts::auth :title="__('تسجيل الدخول')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('تسجيل الدخول إلى حسابك')" :description="__('يرجى إدخال البريد الإلكتروني وكلمة المرور أدناه لتسجيل الدخول')" />


        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />


        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('البريد الإلكتروني')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('كلمة المرور')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('كلمة المرور')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('هل نسيت كلمة المرور؟') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('تذكرني')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('تسجيل الدخول') }}
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts::auth>
