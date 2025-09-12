@extends('env-manager::layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header -->
        @include('env-manager::header')

        <!-- Alerts -->
        @include('env-manager::alert')

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
                                    @php
                                        // Remove quotes if present
                                        $cleanValue = trim($value, '"');
                                    @endphp

                                    <input type="text" name="env[{{ $key }}]" value="{{ $cleanValue ?: '' }}"
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
        @include('env-manager::restore')

    </div>
@endsection
