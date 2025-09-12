<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gray-100 text-gray-800 antialiased flex flex-col min-h-screen transition-colors duration-300 dark:bg-gray-900 dark:text-gray-100">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand -->
                <a href="{{ url('/') }}"
                    class="flex items-center text-xl font-bold text-blue-600 dark:text-blue-400">
                    <span class="bg-blue-600 dark:bg-blue-500 text-white px-2 py-1 rounded-lg mr-2">L</span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}"
                        class="hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
                    <a href="{{ route('env-manager.index') }}"
                        class="hover:text-blue-600 dark:hover:text-blue-400 transition">Env Manager</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hover:text-red-600 dark:hover:text-red-400 transition">Logout</button>
                        </form>
                    @endauth

                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        🌙
                    </button>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button id="mobile-menu-btn"
                        class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        ☰
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700 shadow">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ url('/') }}" class="block hover:text-blue-600 dark:hover:text-blue-400">Home</a>
                <a href="{{ route('env-manager.index') }}"
                    class="block hover:text-blue-600 dark:hover:text-blue-400">Env Manager</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block hover:text-red-600 dark:hover:text-red-400">Logout</button>
                    </form>
                @endauth

                <!-- Dark Mode Toggle (Mobile) -->
                <button id="theme-toggle-mobile"
                    class="w-full text-left p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    🌙 Toggle Dark Mode
                </button>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer
        class="mt-10 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-t dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
            &copy; {{ date('Y') }}
            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ config('app.name') }}</span>.
            All rights reserved.
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Dark Mode Toggle
        const themeToggleBtns = [document.getElementById('theme-toggle'), document.getElementById('theme-toggle-mobile')];
        const html = document.documentElement;

        // Load theme from localStorage
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        themeToggleBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    html.classList.toggle('dark');
                    if (html.classList.contains('dark')) {
                        localStorage.setItem('theme', 'dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                    }
                });
            }
        });
    </script>
</body>

</html>
