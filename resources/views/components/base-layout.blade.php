<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'QR Generator') }}@if (config("route.name") !== null) | {{config("route.name")}} @endif </title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    @if (!Route::is("home"))
        @include('components.navbar')
    @endif
    <main id="main-content">
        {{ $slot }}
    </main>

</body>
</html>
