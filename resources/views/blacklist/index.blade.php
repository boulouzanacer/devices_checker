@extends('layouts.app')

@section('title', 'Liste noire - Device Checker')

@section('content')
    <!-- Page header -->
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-red-700">Liste noire</h1>
    </div>
    <p class="text-sm text-gray-500 mb-6">
        Devices bloqués. Ces devices sont <strong>protégés</strong> et ne peuvent pas être supprimés :
        débloquez-les d'abord pour les retirer de la liste.
    </p>

    <!-- Filters -->
    <form method="GET" action="{{ route('blacklist.index') }}" data-auto-filter class="flex flex-wrap items-center gap-3 mb-6">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un device..."
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
        </div>
        <span class="text-sm text-gray-500 ml-auto">{{ $total }} bloqué(s)</span>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Device</th>
                        <th class="px-6 py-4 font-semibold">Application</th>
                        <th class="px-6 py-4 font-semibold">Version</th>
                        <th class="px-6 py-4 font-semibold">Activation</th>
                        <th class="px-6 py-4 font-semibold">Reseller</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Dernière activité</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($devices as $device)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-red-600">{{ $device->name ?: $device->device_id }}</div>
                                <div class="text-xs font-mono text-red-400">{{ $device->device_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($device->app_name)
                                    <span class="inline-flex items-center gap-1.5 text-gray-700">
                                        <i class="fa-solid fa-mobile-screen-button text-gray-400"></i> {{ $device->app_name }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $device->app_version ?: '—' }}</td>
                            <td class="px-6 py-4">
                                @if($device->activation_code > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activé
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Désactivé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $device->revendeur ?: '—' }}</td>
                            <td class="px-6 py-4">
                                @if($device->is_online)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Offline
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($device->last_seen)
                                    {{ $device->last_seen->diffForHumans() }}
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <span class="p-2 text-gray-300" title="Device en liste noire : non supprimable">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <form method="POST" action="{{ route('devices.toggle-block', $device) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition" title="Débloquer (retirer de la liste noire)">
                                            <i class="fa-solid fa-lock-open"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-shield-halved text-3xl mb-2"></i>
                                <p>Aucun device en liste noire.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $devices->links() }}
        </div>
    </div>
@endsection
