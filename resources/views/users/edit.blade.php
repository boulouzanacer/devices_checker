@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier l'utilisateur</h1>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nouveau mot de passe <span class="text-gray-400 text-xs">(laisser vide pour conserver)</span></label>
                <input type="password" name="password" minlength="6"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Rôle *</label>
                <select name="role" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    Enregistrer
                </button>
                <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Annuler</a>
            </div>
        </form>
    </div>
@endsection
