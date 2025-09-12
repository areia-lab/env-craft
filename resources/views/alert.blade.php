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
