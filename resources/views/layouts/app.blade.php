<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body>
    <x-header/>
        <div class="alertMsg">
            @if(session('success'))
                <x-alert type="success" :message="session('success')"/>
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')"/>
            @endif

            @if(session('info'))
                <x-alert :message="session('info')"/>
            @endif
        </div>

        {{ $slot }}

        @livewireScripts
    </body>
</html>

{{--OPTIONAL IMPROVEMENTS--}}

{{--real time validation--}}
