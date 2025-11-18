<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-DYd95ZtYRv8iMbwFcYkt+..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="app.js"></script>
    <script src="path/to/app-6-kOYhqQ.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-light min-h-screen flex flex-col">
    {{-- Header --}}
    @include('layouts.header')

    {{-- Nội dung trang --}}
    <main class="flex-grow py-4">
        <div class="container mx-auto">
            @hasSection('header')
                <header class="mb-4">
                    <div class="bg-white shadow rounded p-3">
                        @yield('header')
                    </div>
                </header>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
</body>
</html>
