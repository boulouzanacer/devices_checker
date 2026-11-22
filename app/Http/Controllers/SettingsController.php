<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceHistory;
use App\Models\Revendeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Page des parametres de l'application.
     */
    public function index()
    {
        return view('settings.index', [
            'devicesCount' => Device::count(),
            'revendeursCount' => Revendeur::count(),
            'historyCount' => DeviceHistory::count(),
            'usersCount' => User::count(),
        ]);
    }

    /**
     * Reinitialise les donnees de l'application.
     * La table users n'est jamais touchee : seul un administrateur connecte,
     * confirme par son mot de passe, peut declencher cette operation.
     */
    public function resetDatabase(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if (! Hash::check($request->input('password'), $request->user()->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        // Tables de donnees de l'application (users volontairement exclue).
        DB::table('device_histories')->truncate();
        DB::table('devices')->truncate();
        DB::table('revendeurs')->truncate();

        return redirect()->route('settings.index')->with(
            'success',
            'Base de données réinitialisée : devices, revendeurs et historique supprimés. La table users est intacte.'
        );
    }
}
