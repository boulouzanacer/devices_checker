@extends('layouts.app')

@section('title', 'Paramètres - Device Checker')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-700">Paramètres</h1>
    </div>

    {{-- Etat actuel --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 max-w-3xl">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Contenu actuel de la base</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <div class="text-xs text-gray-500">Devices</div>
                <div class="text-xl font-bold text-gray-800">{{ $devicesCount }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <div class="text-xs text-gray-500">Revendeurs</div>
                <div class="text-xl font-bold text-gray-800">{{ $revendeursCount }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <div class="text-xs text-gray-500">Historique</div>
                <div class="text-xl font-bold text-gray-800">{{ $historyCount }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg px-4 py-3">
                <div class="text-xs text-gray-500">Utilisateurs</div>
                <div class="text-xl font-bold text-gray-800">{{ $usersCount }}</div>
            </div>
        </div>
    </div>

    {{-- Zone dangereuse (admin uniquement) --}}
    @if(auth()->user()->isAdmin())
        <div class="bg-white rounded-xl border border-red-200 max-w-3xl">
            <div class="px-6 py-4 border-b border-red-100">
                <h2 class="text-sm font-semibold text-red-600">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Zone dangereuse
                </h2>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600 mb-1">
                    Réinitialiser la base de données supprime définitivement les données suivantes :
                </p>
                <ul class="text-sm text-gray-600 list-disc list-inside mb-4">
                    <li>la liste des <strong>devices</strong></li>
                    <li>la liste des <strong>revendeurs</strong></li>
                    <li>tout l'<strong>historique</strong> (connexions, déconnexions, inscriptions, modifications, suppressions)</li>
                </ul>
                <p class="text-sm text-green-700 mb-5">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    La table <strong>users</strong> n'est pas touchée : vos comptes et mots de passe restent intacts.
                </p>

                <button type="button" onclick="openModal('modal-reset')"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                    <i class="fa-solid fa-trash-can mr-1"></i> Réinitialiser la base de données
                </button>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-3xl text-sm text-gray-500">
            La réinitialisation de la base de données est réservée aux administrateurs.
        </div>
    @endif

    {{-- ========== MODAL : confirmation de réinitialisation ========== --}}
    @if(auth()->user()->isAdmin())
        <div id="modal-reset" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-reset')">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-auto mt-16">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-red-600">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Confirmer la réinitialisation
                    </h3>
                    <button type="button" onclick="closeModal('modal-reset')" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('settings.reset') }}">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        <p class="text-sm text-gray-600">
                            Cette action est <strong class="text-red-600">irréversible</strong>. Les devices, les revendeurs
                            et tout l'historique seront supprimés définitivement. La table <strong>users</strong> sera conservée.
                        </p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mot de passe administrateur *
                            </label>
                            <input type="password" name="password" autocomplete="current-password" placeholder="Saisir votre mot de passe..."
                                   oninput="this.closest('form').querySelector('button[type=submit]').disabled = this.value.length === 0;"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-reset')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Annuler</button>
                        <button type="submit" disabled class="bg-red-600 hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-lg transition">
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

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
