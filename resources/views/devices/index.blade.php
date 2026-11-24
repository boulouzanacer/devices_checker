@extends('layouts.app')

@section('title', 'Devices - Device Checker')

@section('content')
    <!-- Page header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-700">Device Checker - Admin Manager</h1>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('devices.index') }}" data-auto-filter class="flex flex-wrap items-center gap-3 mb-6">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search devices..."
                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select name="revendeur" class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All resellers</option>
            @foreach($revendeurs as $r)
                <option value="{{ $r }}" {{ request('revendeur') === $r ? 'selected' : '' }}>{{ $r }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
        <span class="text-sm text-gray-500 ml-auto">{{ $total }} total</span>
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
                        <th class="px-6 py-4 font-semibold">Last seen</th>
                        <th class="px-6 py-4 font-semibold">Last disconnect</th>
                        <th class="px-6 py-4 font-semibold">Enabled</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($devices as $device)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold {{ $device->is_blocked ? 'text-red-600' : 'text-gray-900' }}">{{ $device->name ?: $device->device_id }}</div>
                                <div class="text-xs font-mono {{ $device->is_blocked ? 'text-red-500' : 'text-gray-400' }}">{{ $device->device_id }}</div>
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
                            <td class="px-6 py-4">
                                @if($device->app_version)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">v{{ $device->app_version }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                                @if($device->package_name)
                                    <div class="text-xs text-gray-400 font-mono break-all mt-1">{{ $device->package_name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($device->activation_code > 0)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold font-mono bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $device->activation_code }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Désactivé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($device->revendeur)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $device->revendeur }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
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
                                    <div class="text-xs text-gray-400">{{ $device->last_seen->format('Y-m-d H:i') }}</div>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($device->last_disconnect)
                                    {{ $device->last_disconnect->diffForHumans() }}
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('devices.toggle-block', $device) }}" class="inline">
                                    @csrf
                                    <label class="toggle-switch">
                                        <input type="checkbox" {{ !$device->is_blocked ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" onclick="openModal('modal-info-{{ $device->id }}')" class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition" title="Infos">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <button type="button" onclick="openModal('modal-edit-{{ $device->id }}')" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" onclick="openModal('modal-delete-{{ $device->id }}')" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                                <p>Aucun device enregistré.</p>
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

    <!-- ========== MODAL : Ajouter un device ========== -->
    <div id="modal-add" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-add')">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-auto mt-16">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Ajouter un device</h3>
                <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('devices.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Device ID *</label>
                        <input type="text" name="device_id" required
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                        <input type="text" name="name"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Revendeur</label>
                        <input type="text" name="revendeur"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modal-add')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Annuler</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($devices as $device)
        <!-- ========== MODAL : Infos device {{ $device->id }} ========== -->
        <div id="modal-info-{{ $device->id }}" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-info-{{ $device->id }}')">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-auto mt-16">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Détails du device</h3>
                    <button type="button" onclick="closeModal('modal-info-{{ $device->id }}')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Device ID</dt>
                            <dd class="font-mono text-gray-900 break-all">{{ $device->device_id }}</dd>
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
                            <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Application</dt>
                            <dd class="text-gray-900">{{ $device->app_name ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">App version</dt>
                            <dd class="text-gray-900">{{ $device->app_version ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Package</dt>
                            <dd class="text-gray-900 font-mono text-xs break-all">{{ $device->package_name ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 uppercase text-xs tracking-wider mb-1">Code d'activation</dt>
                            <dd>
                                @if($device->activation_code > 0)
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold font-mono bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $device->activation_code }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Désactivé
                                    </span>
                                @endif
                            </dd>
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
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('devices.toggle-block', $device) }}">
                        @csrf
                        <button type="submit" class="{{ $device->is_blocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white font-semibold px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid {{ $device->is_blocked ? 'fa-unlock' : 'fa-lock' }}"></i>
                            {{ $device->is_blocked ? 'Débloquer' : 'Bloquer' }}
                        </button>
                    </form>
                    <button type="button" onclick="closeModal('modal-info-{{ $device->id }}')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Fermer</button>
                </div>
            </div>
        </div>

        <!-- ========== MODAL : Modifier device {{ $device->id }} ========== -->
        <div id="modal-edit-{{ $device->id }}" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-edit-{{ $device->id }}')">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-auto mt-16">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Modifier le device</h3>
                    <button type="button" onclick="closeModal('modal-edit-{{ $device->id }}')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form method="POST" action="{{ route('devices.update', $device) }}">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Device ID</label>
                            <input type="text" value="{{ $device->device_id }}" disabled
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-gray-50 text-gray-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                            <input type="text" name="name" value="{{ $device->name }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Revendeur</label>
                            <select name="revendeur"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @if($device->revendeur && ! collect($revendeurs)->contains($device->revendeur))
                                    <option value="{{ $device->revendeur }}" selected>{{ $device->revendeur }}</option>
                                @endif
                                @foreach($revendeurs as $r)
                                    <option value="{{ $r }}" {{ $device->revendeur === $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-edit-{{ $device->id }}')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Annuler</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========== MODAL : Supprimer device {{ $device->id }} ========== -->
        <div id="modal-delete-{{ $device->id }}" class="modal hidden fixed inset-0 z-50 bg-black/40 p-4 overflow-y-auto" onclick="backdropClose(event, 'modal-delete-{{ $device->id }}')">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-auto mt-16">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-red-600"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Supprimer le device</h3>
                    <button type="button" onclick="closeModal('modal-delete-{{ $device->id }}')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form method="POST" action="{{ route('devices.destroy', $device) }}">
                    @csrf
                    @method('DELETE')
                    <div class="px-6 py-5 space-y-4">
                        <p class="text-sm text-gray-600">
                            Cette action est <strong class="text-red-600">irréversible</strong>. Pour confirmer la suppression,
                            saisissez le numéro de série du device ci-dessous.
                        </p>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-mono text-gray-700">
                            {{ $device->device_id }}
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Numéro de série *</label>
                            <input type="text" autocomplete="off" placeholder="Saisir le numéro de série..."
                                   oninput="this.closest('form').querySelector('button[type=submit]').disabled = this.value.trim().toUpperCase() !== @js(strtoupper($device->device_id));"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-delete-{{ $device->id }}')" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2">Annuler</button>
                        <button type="submit" disabled class="bg-red-600 hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-lg transition">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

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
