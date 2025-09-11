@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-8">
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-2xl font-bold mb-4">Laravel Env Manager</h2>

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-3">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-3">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('env-manager.save') }}" class="space-y-4">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-full border rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Key</th>
                                <th class="px-4 py-2 text-left">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($env as $k => $v)
                                <tr class="border-t">
                                    <td class="px-4 py-2 font-mono text-sm">{{ $k }}</td>
                                    <td class="px-4 py-2">
                                        <input class="w-full border rounded p-2" name="env[{{ $k }}]"
                                            value="{{ $v }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </form>

            <hr class="my-6">

            <form method="POST" action="{{ route('env-manager.backup') }}" class="inline-block">
                @csrf
                <button class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Create Backup</button>
            </form>

            <form method="POST" action="{{ route('env-manager.restore') }}" class="mt-6 space-y-2">
                @csrf
                <label class="block font-medium">Select backup to restore</label>
                <select name="backup" class="w-full border rounded p-2">
                    @foreach ($backups as $b)
                        <option value="{{ $b }}">{{ basename($b) }} ({{ date('Y-m-d H:i:s', filemtime($b)) }})
                        </option>
                    @endforeach
                </select>
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Restore Selected Backup</button>
            </form>
        </div>
    </div>
@endsection
