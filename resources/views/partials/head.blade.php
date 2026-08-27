<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'North Car Parts') : config('app.name', 'North Car Parts') }}
</title>

<link rel="icon" type="image/png" href="{{ asset('assets/favicon/favicon-96x96.png') }}" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon/favicon.svg') }}" />
<link rel="shortcut icon" href="{{ asset('assets/favicon/favicon.ico') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}" />
<meta name="apple-mobile-web-app-title" content="North Car Parts" />
<link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}" />

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
