<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Codura') }} - Public Portfolio</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <main class="min-h-screen py-10 px-6">
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>
