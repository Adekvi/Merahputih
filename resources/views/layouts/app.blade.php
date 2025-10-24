<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggleDark() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              document.documentElement.classList.toggle('dark', this.darkMode);
          } 
      }"
      x-init="document.documentElement.classList.toggle('dark', darkMode)"
      x-bind:class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine store untuk sidebar --}}
    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', { open: false });
      });
    </script>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans antialiased bg-white text-gray-900 dark:bg-[#0d1325] dark:text-gray-100 h-full">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full lg:translate-x-0 
               transition-transform duration-200 ease-in-out bg-gray-100 dark:bg-[#101836] shadow-lg"
        :class="{ 'translate-x-0': $store.sidebar.open }"
        @keydown.window.escape="$store.sidebar.open = false"
    >
        <div class="p-4 flex items-center justify-between lg:justify-center">
            <h1 class="text-lg font-bold text-gray-800 dark:text-white">Menu</h1>

            {{-- Tombol Tutup (Mobile) --}}
            <button @click="$store.sidebar.open = false" class="lg:hidden p-2 rounded-md focus:outline-none focus:ring">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="px-2 py-4">
            @include('layouts.navigation')
        </nav>
    </aside>

    {{-- OVERLAY (Mobile) --}}
    <div
        class="fixed inset-0 z-30 bg-black bg-opacity-40 lg:hidden transition-opacity"
        x-show="$store.sidebar.open"
        x-transition.opacity
        @click="$store.sidebar.open = false"
        x-cloak
    ></div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 min-h-screen flex flex-col lg:ml-64">

        {{-- 🔹 TOPBAR (Mobile) --}}
        <header class="w-full bg-transparent lg:hidden border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    {{-- Tombol Hamburger --}}
                    <button @click="$store.sidebar.open = true" class="p-2 rounded-md focus:outline-none focus:ring">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <h2 class="text-l   g font-semibold text-gray-800 dark:text-white">
                        Koperasi Merah Putih
                    </h2>
                </div>

                {{-- Tombol Dark Mode (Mobile) --}}
                <div class="flex items-center">
                    <button @click="toggleDark()" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h1M3 12H2m15.364 6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M12 5a7 7 0 110 14a7 7 0 010-14z" />
                        </svg>
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.003 8.003 0 1010.586 10.586z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- 🔹 HEADER (Desktop) --}}
        <header class="hidden lg:flex items-center justify-between bg-gray-200 dark:bg-[#101836] shadow px-6 py-3">
            <div>
                @if (isset($header))
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ $header }}
                    </h2>
                @else
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                       Koperasi Merah Putih
                    </h2>
                @endif
            </div>

            {{-- Tombol Dark Mode (Desktop) --}}
            <button @click="toggleDark()" class="p-2 rounded-full bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h1M3 12H2m15.364 6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M12 5a7 7 0 110 14a7 7 0 010-14z" />
                </svg>
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.003 8.003 0 1010.586 10.586z" />
                </svg>
            </button>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 bg-gray-50 dark:bg-[#0d1325] transition-colors duration-300">
            {{ $slot }}
        </main>
    </div>
</div>

@stack('modals')
</body>
</html>
