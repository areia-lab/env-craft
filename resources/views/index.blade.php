@extends('env-manager::layouts.app')

@section('title', 'Env Manager')

@section('content')
    <div class="max-w-6xl mx-auto my-6 py-10 px-4">
        <div class="bg-white shadow-lg rounded-2xl p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800">⚙️ Laravel Env Manager</h2>
                <span class="text-sm text-gray-500">Manage and back up your <code>.env</code> file</span>
            </div>

            <!-- Alerts -->
            @if (session('error'))
                <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg p-3 mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 text-green-700 border border-green-300 rounded-lg p-3 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Env Table -->
            <form method="POST" action="{{ route('env-manager.save') }}" class="space-y-6">
                @csrf
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Key</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($env as $k => $v)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $k }}</td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="env[{{ $k }}]" value="{{ $v }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-400">
                        💾 Save Changes
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="my-10 border-t border-gray-200"></div>

            <!-- Backup Section -->
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-800">📦 Backup</h3>
                <form method="POST" action="{{ route('env-manager.backup') }}">
                    @csrf
                    <button
                        class="bg-gray-700 text-white px-6 py-2 rounded-lg shadow hover:bg-gray-800 focus:ring-2 focus:ring-gray-500">
                        ➕ Create Backup
                    </button>
                </form>
            </div>

            <!-- Restore Section -->
            <div class="space-y-4 mt-8">
                <h3 class="text-xl font-semibold text-gray-800">♻️ Restore</h3>
                <form method="POST" action="{{ route('env-manager.restore') }}" class="space-y-4">
                    @csrf
                    <label class="block text-gray-700 font-medium">Select a backup to restore:</label>
                    <select name="backup"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($backups as $b)
                            <option value="{{ $b }}">
                                {{ basename($b) }} — {{ date('Y-m-d H:i:s', filemtime($b)) }}
                            </option>
                        @endforeach
                    </select>
                    <button
                        class="bg-red-600 text-white px-6 py-2 rounded-lg shadow hover:bg-red-700 focus:ring-2 focus:ring-red-400">
                        🔄 Restore Selected Backup
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
