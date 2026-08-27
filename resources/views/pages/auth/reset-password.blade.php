<x-layouts::auth :title="__('إعادة تعيين كلمة المرور')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('إعادة تعيين كلمة المرور')" :description="__('يرجى إدخال كلمة المرور الجديدة أدناه')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('البريد الإلكتروني')"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('كلمة المرور')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('كلمة المرور')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('تأكيد كلمة المرور')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('تأكيد كلمة المرور')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('إعادة تعيين كلمة المرور') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
