<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    /**
     * Liste noire : devices bloques depuis l'interface.
     *
     * Ces devices sont proteges et ne peuvent pas etre supprimes : il faut
     * d'abord les debloquer. Cela evite qu'une suppression ne leve le blocage
     * (un device supprime est automatiquement re-enregistre comme actif au
     * prochain lancement de l'application mobile).
     */
    public function index(Request $request)
    {
        $query = Device::where('is_blocked', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('device_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $devices = $query->orderByDesc('last_seen')->paginate(15)->withQueryString();
        $total = Device::where('is_blocked', true)->count();

        return view('blacklist.index', compact('devices', 'total'));
    }
}
