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
                    Create Backup
                </button>
            </form>
        </div>
    </div>
