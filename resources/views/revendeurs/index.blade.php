@extends('layouts.app')

@section('title', 'Revendeurs - Device Checker')

@section('content')
    <!-- Page header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-700">Liste des revendeurs</h1>
        <span class="text-sm text-gray-500">{{ $revendeurs->total() }} revendeur(s)</span>
    </div>

    <p class="text-sm text-gray-500 mb-4">
        Les revendeurs sont enregistrés automatiquement depuis l'API lors de la première connexion d'un device.
    </p>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Revendeur</th>
                        <th class="px-6 py-4 font-semibold">Devices</th>
                        <th class="px-6 py-4 font-semibold">Ajouté le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($revendeurs as $revendeur)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                        <i class="fa-solid fa-store text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $revendeur->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('devices.index', ['revendeur' => $revendeur->name]) }}"
                                   class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100">
                                    {{ $revendeur->devices_count }} device(s)
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $revendeur->created_at ? $revendeur->created_at->format('Y-m-d H:i') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-store text-3xl mb-2"></i>
                                <p>Aucun revendeur enregistré.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $revendeurs->links() }}
        </div>
    </div>
@endsection
