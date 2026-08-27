<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        @php($title = __('Welcome'))
        @include('partials.head')
    </head>
    <body class="flex min-h-screen flex-col bg-zinc-50 text-zinc-900 antialiased">
        <main class="flex flex-1 flex-col items-center justify-center px-6 pb-12 pt-4 sm:px-8">
            <div class="mx-auto flex w-full max-w-md flex-col items-center gap-8 text-center">
                <img
                    src="{{ asset('assets/images/logo-blue-and-gray.svg') }}"
                    alt="{{ config('app.name', 'North Car Parts') }}"
                    class="w-full max-w-md"
                />

                <div class="space-y-3">
                    <h1 class="sr-only">{{ config('app.name', 'North Car Parts') }}</h1>
                </div>

                <div class="flex flex-col items-center gap-4 sm:flex-row">
                    @guest
                        <a
                            href="{{ route('login') }}"
                            class="text-sm text-blue-600 transition hover:text-blue-700 hover:underline"
                        >
                            تسجيل الدخول
                        </a>
                    @else
                        <a
                            href="{{ route('dashboard.index') }}"
                            class="text-sm rounded-lg border border-zinc-200 bg-white px-4 py-2 text-zinc-700 transition hover:border-zinc-300 hover:text-zinc-900"
                        >
                            لوحة التحكم
                        </a>

                    @endguest
                </div>
            </div>
        </main>

        <footer class="px-6 py-6 text-center text-sm text-zinc-500 sm:px-8 space-x-1">
            <span class="font-mono">{{ date('Y') }}</span>
            <span class="font-mono">&copy;</span>
            <span>{{ config('app.name', 'North Car Parts') }}</span>
        </footer>
    </body>
</html>
