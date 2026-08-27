@if (session()->has('toast'))
    <div
        x-data="flashToast({ toast: @js(session('toast')) })"
        hidden
    ></div>
@endif
