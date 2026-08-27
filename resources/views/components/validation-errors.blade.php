@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'relative p-4 rounded-md bg-red-50']) }} x-data="{ show: true }" x-show="show" x-on:validation-errors.window="show = true; window.scrollTo({top: 0, behavior: 'smooth'})">
        <svg x-on:click="show = false" class="absolute w-5 h-5 text-red-800 cursor-pointer left-4 top-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>

        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="w-4 h-4 mt-0.5 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="ms-3">
                <h3 class="text-sm font-medium text-red-800">{{ __('alerts.messages.wrong') }}</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul role="list" class="ps-5 space-y-2 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
