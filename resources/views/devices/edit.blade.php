@extends('layouts.app')

@section('title', 'Modifier le device')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier le device</h1>
        <a href="{{ route('devices.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('devices.update', $device) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Device ID</label>
                <input type="text" value="{{ $device->device_id }}" disabled
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-gray-50 text-gray-500 font-mono">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                <input type="text" name="name" value="{{ old('name', $device->name) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Revendeur</label>
                <input type="text" name="revendeur" value="{{ old('revendeur', $device->revendeur) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    Enregistrer
                </button>
                <a href="{{ route('devices.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Annuler</a>
            </div>
        </form>
    </div>
@endsection
