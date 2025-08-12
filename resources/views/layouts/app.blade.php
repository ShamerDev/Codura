<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon2.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @wireUiScripts
    @livewireStyles
</head>

<body x-data class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col">
        {{-- Top Navigation Bar --}}
        <livewire:layout.topnavbar />

        {{-- Main Content Area --}}
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    <x-notifications position="top-end" z-index="z-50" timeout="1000" />
</body>

</html>
