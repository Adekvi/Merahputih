<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-gradient-to-br from-gray-100 via-white to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-500">

    <div class="min-h-screen flex flex-col lg:flex-row items-center justify-center">

        <!-- LEFT PANEL -->
        <div
            class="flex-1 flex flex-col justify-center items-center w-full max-w-md p-8 bg-white dark:bg-gray-800 shadow-2xl rounded-none lg:rounded-r-3xl z-10">
            <div class="flex flex-col items-center mb-8">
                {{-- <a href="/">
                    <x-application-logo class="w-16 h-16 text-red-600 dark:text-red-400" />
                </a> --}}
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-3">{{ config('app.name', 'LOGIN') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Selamat datang kembali</p>
            </div>

            <div class="w-full">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} — All Rights Reserved.
            </div>
        </div>

        <!-- RIGHT PANEL (IMAGE / ILLUSTRATION) -->
        <div class="hidden lg:flex flex-1 items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-l from-red-600/50 to-transparent rounded-l-3xl"></div>
            <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1200&q=80"
                alt="Koperasi Illustration"
                class="object-cover w-full h-screen rounded-l-3xl brightness-95 dark:brightness-75">
            <div class="absolute bottom-20 left-16 text-white">
                <h2 class="text-4xl font-bold drop-shadow-lg">Bersama Membangun Ekonomi</h2>
                <p class="text-lg opacity-90">Koperasi Merah Putih — Mandiri, Adil, dan Sejahtera</p>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button id="themeToggle" type="button"
        class="fixed top-5 right-5 bg-gray-200 dark:bg-gray-700 p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition z-50">
        <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707M17.657 17.657l.707-.707M6.343 6.343l-.707-.707" />
        </svg>
    </button>

    <script>
        // Dark mode toggle
        const btn = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        btn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            icon.innerHTML = document.documentElement.classList.contains('dark') ?
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 12.79A9 9 0 0112.79 3 9 9 0 0021 12.79z" />` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d='M12 3v1m0 16v1m9-9h1M4 12H3m15.364-7.364l.707.707M6.343 17.657l-.707.707M17.657 17.657l.707-.707M6.343 6.343l-.707-.707' />`;
        });

        // Apply saved theme
        if (localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</body>

</html>
