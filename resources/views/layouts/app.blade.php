<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Env Manager</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideIn: {
                            '0%': {
                                transform: 'translateY(-10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .card-hover {
                @apply transition-all duration-300 hover:shadow-lg;
            }

            .btn {
                @apply px-4 py-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
            }

            .btn-primary {
                @apply bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500;
            }

            .btn-secondary {
                @apply bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600;
            }

            .btn-danger {
                @apply bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
            }

            .input-field {
                @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white;
            }
        }
    </style>
</head>

<body
    class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen transition-colors duration-300 dark:bg-gray-900 dark:text-gray-100">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand -->
                <a href="{{ route('env-manager.index') }}"
                    class="flex items-center text-xl font-bold text-primary-600 dark:text-primary-400">
                    <span
                        class="bg-primary-600 dark:bg-primary-500 text-white px-2 py-1 rounded-lg mr-2">{{ config('env-manager.panel.title_prefix', 'Env') }}</span>
                    {{ config('env-manager.panel.title_suffix', 'Craft') }}
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url(config('env-manager.panel.call_back_url', '/')) }}"
                        class="hover:text-primary-600 dark:hover:text-primary-400 transition">{{ config('env-manager.panel.call_back_title', 'Home') }}</a>
                    <a href="{{ route('env-manager.index') }}"
                        class="hover:text-primary-600 dark:hover:text-primary-400 transition">Env Manager</a>
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
                        class="text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400">
                        ☰
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700 shadow-md">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ url('/') }}" class="block hover:text-primary-600 dark:hover:text-primary-400">Home</a>
                <a href="{{ route('env-manager.index') }}"
                    class="block hover:text-primary-600 dark:hover:text-primary-400">Env Manager</a>
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
    <main class="flex-1 py-8">
        @yield('content')

    </main>

    <!-- Footer -->
    <footer
        class="mt-10 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-t dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
            &copy; {{ date('Y') }}
            <span class="font-semibold text-primary-600 dark:text-primary-400">{{ config('app.name') }}</span>.
            All rights reserved.
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('animate-slide-in');
            });
        }

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

        // Toggle section visibility
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const iconId = sectionId.replace('section', 'icon');
            const icon = document.getElementById(iconId);

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                icon.classList.remove('rotate-0');
                icon.classList.add('rotate-180');
            } else {
                section.classList.add('hidden');
                icon.classList.remove('rotate-180');
                icon.classList.add('rotate-0');
            }
        }
    </script>
</body>

</html>
