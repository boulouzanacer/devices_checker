<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Device Checker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f3f4f6; }
        .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider { position: absolute; cursor: pointer; inset: 0; background-color: #e5e7eb; border-radius: 24px; transition: .3s; }
        .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .3s; }
        input:checked + .toggle-slider { background-color: #6366f1; }
        input:checked + .toggle-slider:before { transform: translateX(20px); }
    </style>
</head>
<body class="min-h-screen">

    <!-- Top header bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-6 h-16">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="font-bold text-indigo-600 text-lg leading-none">DEVICE CHECKER</div>
                    <div class="text-xs text-gray-400 leading-none mt-0.5">Admin Manager</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-gray-700"><i class="fa-regular fa-bell"></i></button>
                <div class="flex items-center gap-2 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-60 bg-white border-r border-gray-200 min-h-[calc(100vh-4rem)] p-4">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Admin</div>
            <nav class="space-y-1">
                <a href="{{ route('devices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('devices.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-desktop w-4"></i> Devices
                </a>
                <a href="{{ route('revendeurs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('revendeurs.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-store w-4"></i> Revendeurs
                </a>
                <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('history.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4"></i> Historique
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-users w-4"></i> Users
                </a>
                @endif
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-gear w-4"></i> Settings
                </a>
            </nav>

            <div class="mt-8 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 w-full">
                        <i class="fa-solid fa-right-from-bracket w-4"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Application automatique des filtres :
        //  - selects et champs date : soumission au changement
        //  - champ recherche : soumission sur la touche Entrée
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-auto-filter]').forEach(function (form) {
                form.querySelectorAll('select, input[type="date"]').forEach(function (el) {
                    el.addEventListener('change', function () { form.submit(); });
                });
                var search = form.querySelector('input[name="search"]');
                if (search) {
                    search.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') { e.preventDefault(); form.submit(); }
                    });
                }
            });
        });
    </script>

</body>
</html>
