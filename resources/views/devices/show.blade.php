@extends('layouts.app')

@section('title', 'Détails du device')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Détails du device</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('devices.edit', $device) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-pen-to-square"></i> Modifier
            </a>
            <a href="{{ route('devices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3"><i class="fa-solid fa-arrow-left"></i> Retour</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Info card -->
        <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Device ID</dt>
                    <dd class="font-mono text-gray-900">{{ $device->device_id }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Nom</dt>
                    <dd class="text-gray-900">{{ $device->name ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Revendeur</dt>
                    <dd class="text-gray-900">{{ $device->revendeur ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Statut</dt>
                    <dd>
                        @if($device->is_blocked)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Bloqué
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Actif
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">App version</dt>
                    <dd class="text-gray-900">{{ $device->app_version ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Package</dt>
                    <dd class="text-gray-900 font-mono text-xs">{{ $device->package_name ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Première connexion</dt>
                    <dd class="text-gray-900">{{ $device->first_seen ? $device->first_seen->format('Y-m-d H:i') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Dernière connexion</dt>
                    <dd class="text-gray-900">{{ $device->last_seen ? $device->last_seen->format('Y-m-d H:i') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Dernière déconnexion</dt>
                    <dd class="text-gray-900">{{ $device->last_disconnect ? $device->last_disconnect->format('Y-m-d H:i') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Heartbeats</dt>
                    <dd class="text-gray-900">{{ $device->heartbeat_count ?? 0 }}</dd>
                </div>
            </dl>
        </div>

        <!-- Status card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
            <form method="POST" action="{{ route('devices.toggle-block', $device) }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full {{ $device->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-semibold px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid {{ $device->is_blocked ? 'fa-unlock' : 'fa-lock' }}"></i>
                    {{ $device->is_blocked ? 'Débloquer' : 'Bloquer' }}
                </button>
            </form>
            <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('Supprimer ce device ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-gray-100 hover:bg-red-50 text-red-600 font-semibold px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
@endsection
