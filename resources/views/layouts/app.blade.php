@props(['backUrl' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center gap-4">
                    @if ($backUrl)
                        <a href="{{ $backUrl }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-lg flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @endif

                    <div class="flex-1">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endif

        @if (session('success'))
            <x-toast type="success" :messages="session('success')" />
        @endif

        @if ($errors->any())
            <x-toast type="error" :messages="$errors->all()" />
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
