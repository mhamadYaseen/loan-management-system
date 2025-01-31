<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased bg-gray-50">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Loan Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        

        <!-- Scripts and Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Include Alpine.js via CDN -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <!-- Additional Styles -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @livewireStyles
    </head>

    <body class="min-h-screen bg-gray-50">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <div class="hidden md:flex md:w-64 md:flex-col">
                <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-white border-r">
                    {{-- <div class="flex items-center flex-shrink-0 px-4">
               <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo">
            </div> --}}
                    <div class="mt-5 flex-grow flex flex-col">
                        <nav class="flex-1 px-2 space-y-1">
                            <!-- Navigation Items -->
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1">
                <!-- Top Navigation -->
                <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow">
                    <div class="flex flex-1 justify-between px-4">
                        <div class="flex flex-1">
                            <!-- Header Content -->
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            @yield('content')
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
        <script src="//unpkg.com/alpinejs" defer></script>
    </body>

</html>
