<x-layouts::auth :title="__('هل نسيت كلمة المرور؟')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('هل نسيت كلمة المرور؟')" :description="__('يرجى إدخال البريد الإلكتروني أدناه لتلقي رابط إعادة تعيين كلمة المرور')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('البريد الإلكتروني')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('إرسال رابط إعادة تعيين كلمة المرور') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('أو, عودة إلى') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('تسجيل الدخول') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
