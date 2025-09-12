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
                <a href="{{ url('/') }}"
                    class="flex items-center text-xl font-bold text-primary-600 dark:text-primary-400">
                    <span class="bg-primary-600 dark:bg-primary-500 text-white px-2 py-1 rounded-lg mr-2">L</span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}"
                        class="hover:text-primary-600 dark:hover:text-primary-400 transition">Home</a>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">⚙️</span>
                        <span>Env Manager</span>
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your Laravel <code
                            class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-sm">.env</code> configurations
                        efficiently</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('env-manager.backup') }}">
                        @csrf
                        <button class="btn btn-primary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Backup
                        </button>
                    </form>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 animate-fade-in dark:bg-red-900/20 dark:border-red-700 dark:text-red-300"
                    role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 animate-fade-in dark:bg-green-900/20 dark:border-green-700 dark:text-green-300"
                    role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Env Groups -->
            <form method="POST" action="{{ route('env-manager.save') }}" class="space-y-6">
                @csrf
                <div class="grid lg:grid-cols-2 gap-6">
                    @foreach ($groups as $groupName => $variables)
                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm card-hover">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-primary-100 text-primary-700 rounded-full text-center font-bold dark:bg-primary-900/40 dark:text-primary-300">
                                        {{ substr($groupName, 0, 1) }}
                                    </span>
                                    <span>{{ $groupName }}</span>
                                </h3>
                                <button type="button" onclick="toggleSection('section-{{ $loop->index }}')"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none transition-transform"
                                    aria-label="Toggle section">
                                    <svg id="icon-{{ $loop->index }}" xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transform rotate-180" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </div>
                            <div id="section-{{ $loop->index }}" class="space-y-4">
                                @foreach ($variables as $key => $value)
                                    <div class="flex flex-col">
                                        <label
                                            class="text-gray-700 dark:text-gray-300 font-medium mb-1 flex justify-between items-center">
                                            <span>{{ $key }}</span>
                                            <button type="button" onclick="copyToClipboard('{{ $value }}')"
                                                class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 flex items-center gap-1"
                                                title="Copy value">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                                Copy
                                            </button>
                                        </label>
                                        <input type="text" name="env[{{ $key }}]"
                                            value="{{ $value }}" class="input-field">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn btn-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>

            <!-- Restore Section -->
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm card-hover mt-10">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Restore from Backup
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Select a backup to restore your <code
                        class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-sm">.env</code> file</p>
                <form method="POST" action="{{ route('env-manager.restore') }}"
                    class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                    @csrf
                    <select name="backup" class="input-field md:max-w-md">
                        @foreach ($backups as $b)
                            <option value="{{ $b }}">
                                {{ basename($b) }} — {{ date('Y-m-d H:i:s', filemtime($b)) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Restore Selected Backup
                    </button>
                </form>
            </div>

        </div>
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

        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show a temporary notification that the value was copied
                const notification = document.createElement('div');
                notification.innerText = 'Copied to clipboard!';
                notification.className =
                    'fixed bottom-4 right-4 bg-primary-600 text-white px-4 py-2 rounded-lg shadow-lg animate-fade-in z-50';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.remove('animate-fade-in');
                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        // Initialize all sections as expanded by default
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('[id^="section-"]');
            sections.forEach(section => {
                section.classList.remove('hidden');
            });
        });
    </script>
</body>

</html>
