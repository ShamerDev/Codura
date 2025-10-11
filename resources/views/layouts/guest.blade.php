<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SEEK-AI') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @wireUiStyles
    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Logo Section -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <a href="/" wire:navigate class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-200 group-hover:scale-105">
                        <span class="text-white font-mono font-bold text-lg">&lt;/&gt;</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">SEEK-AI</span>
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} SEEK-AI.
                <a href="#" class="text-indigo-600 hover:text-indigo-500 transition-colors">Privacy</a> •
                <a href="#" class="text-indigo-600 hover:text-indigo-500 transition-colors">Terms</a>
            </p>
        </div>
    </div>

    <!-- WireUI global notification component -->
    <x-notifications />

    <!-- Scripts -->
    @wireUiScripts
    @livewireScripts
</body>

</html>
