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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex h-6 space-between w-full items-center">
                    <div class="w-full">
                        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                            {{ __('Orders') }}
                        </h3>
                        <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                            {{ __('Orders') }}
                        </h3>
                    </div>
                    <div class="w-full flex justify-end">

                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button onclick='window.location="/pos/initiate-payment"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50' , 'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white dark:bg-gray-50'=> !request()->routeIs('payment.create'),
                                'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('payment.create'),
                                ])>
                                Initiate Payment
                            </button>
                            <button onclick='window.location="/pos/payments"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium border-t border-b focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white' , 'bg-white text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white'=> !request()->routeIs('payment.index'),
                                'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('payment.index'),
                                ])>
                                Payment History
                            </button>
                            <button onclick='window.location="/pos/inventory"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-e-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white' , 'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white'=> !request()->routeIs('inventory'),
                                'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('inventory'),
                                ])>
                                Inventory
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>