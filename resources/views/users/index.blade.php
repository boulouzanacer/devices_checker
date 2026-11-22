@extends('layouts.app')

@section('title', 'Utilisateurs - Device Checker')

@section('content')
    <!-- Page header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-700">Gestion des utilisateurs</h1>
        <button type="button" onclick="openModal('modal-add-user')" class="bg-gray-900 hover:bg-black text-white text-sm font-semibold px-5 py-2.5 rounded-lg flex items-center gap-2 transition">
            <i class="fa-solid fa-plus"></i> ADD USER
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Nom</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Rôle</th>
                        <th class="px-6 py-4 font-semibold">Créé le</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                        <i class="fa-solid fa-crown"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <i class="fa-solid fa-user"></i> User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-users text-3xl mb-2"></i>
                                <p>Aucun utilisateur.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- ========== MODAL : Ajouter un utilisateur ========== -->
    <div id="modal-add-user" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-add-user')">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-auto mt-16">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Ajouter un utilisateur</h3>
                <button type="button" onclick="closeModal('modal-add-user')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom *</label>
                        <input type="text" name="name" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                        <input type="email" name="email" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe *</label>
                        <input type="password" name="password" required minlength="6"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Rôle *</label>
                        <select name="role" required
                                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modal-add-user')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Annuler</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">Créer l'utilisateur</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            var m = document.getElementById(id);
            if (!m) return;
            m.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal(id) {
            var m = document.getElementById(id);
            if (!m) return;
            m.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        function backdropClose(event, id) {
            if (event.target === event.currentTarget) {
                closeModal(id);
            }
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(function (m) {
                    m.classList.add('hidden');
                });
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
@endsection
