@extends('layouts.app')

@section('title', 'Historique - Device Checker')

@section('content')
    <!-- Page header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-700">Historique des devices</h1>
        <span class="text-sm text-gray-500">{{ $total }} événement(s)</span>
    </div>

    <!-- Filtres / recherche -->
    <form method="GET" action="{{ route('history.index') }}" data-auto-filter class="flex flex-wrap items-center gap-3 mb-6">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un device..."
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select name="event" class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Tous les événements</option>
            @foreach($events as $key => $label)
                <option value="{{ $key }}" {{ request('event') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="revendeur" class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Tous les revendeurs</option>
            @foreach($revendeurs as $r)
                <option value="{{ $r }}" {{ request('revendeur') === $r ? 'selected' : '' }}>{{ $r }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" title="Du"
               class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <input type="date" name="to" value="{{ request('to') }}" title="Au"
               class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @if(request()->hasAny(['search', 'event', 'revendeur', 'from', 'to']))
            <a href="{{ route('history.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2">Réinitialiser</a>
        @endif
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Événement</th>
                        <th class="px-6 py-4 font-semibold">Device</th>
                        <th class="px-6 py-4 font-semibold">Revendeur</th>
                        <th class="px-6 py-4 font-semibold">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($histories as $history)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $history->created_at ? $history->created_at->format('Y-m-d H:i:s') : '—' }}
                                <div class="text-xs text-gray-400">{{ $history->created_at ? $history->created_at->diffForHumans() : '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = [
                                        'registered' => 'bg-blue-50 text-blue-600',
                                        'connected' => 'bg-green-50 text-green-600',
                                        'disconnected' => 'bg-gray-100 text-gray-500',
                                        'blocked' => 'bg-red-50 text-red-600',
                                        'unblocked' => 'bg-emerald-50 text-emerald-600',
                                        'updated' => 'bg-amber-50 text-amber-600',
                                        'deleted' => 'bg-red-50 text-red-600',
                                    ][$history->event] ?? 'bg-gray-100 text-gray-500';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $history->event_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $history->device_name ?: '—' }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $history->device_id ?: '—' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($history->revendeur)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $history->revendeur }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $history->description ?: '—' }}
                                @if($history->ip_address)
                                    <div class="text-xs text-gray-400 font-mono">{{ $history->ip_address }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-clock-rotate-left text-3xl mb-2"></i>
                                <p>Aucun événement dans l'historique.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $histories->links() }}
        </div>
    </div>
@endsection
