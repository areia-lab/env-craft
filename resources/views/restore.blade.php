    <div
        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm card-hover mt-10">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
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
