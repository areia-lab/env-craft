@extends('env-manager::layouts.app')

@section('content')
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
                                    📂
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                            Copy
                                        </button>
                                    </label>
                                    <input type="text" name="env[{{ $key }}]" value="{{ $value }}"
                                        class="input-field">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
@endsection
