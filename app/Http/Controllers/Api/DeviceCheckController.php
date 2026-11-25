<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceHistory;
use App\Models\Revendeur;
use Illuminate\Http\Request;

class DeviceCheckController extends Controller
{
    /**
     * Enregistre un device (si nouveau) ou pointe sa présence (heartbeat).
     * Retourne la décision : active | blocked.
     */
    public function check(Request $request)
    {
        $data = $request->validate([
            'device_id' => ['required', 'string', 'max:100'],
            'revendeur' => ['nullable', 'string', 'max:100'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'package_name' => ['nullable', 'string', 'max:100'],
            'app_name' => ['nullable', 'string', 'max:100'],
            // Code d'activation envoye par l'application mobile (0 = non activee).
            // Champ optionnel pour rester compatible avec les anciennes versions.
            'activation_code' => ['nullable', 'integer', 'min:0'],
        ]);

        // Enregistre le revendeur recu depuis l'API dans la liste des revendeurs
        // s'il n'existe pas encore.
        $this->registerRevendeur($data['revendeur'] ?? null);

        $now = now();
        $device = Device::where('device_id', $data['device_id'])->first();

        if (!$device) {
            $device = Device::create([
                'device_id' => $data['device_id'],
                'revendeur' => $data['revendeur'] ?? null,
                'app_version' => $data['app_version'] ?? null,
                'package_name' => $data['package_name'] ?? null,
                'app_name' => $data['app_name'] ?? null,
                'activation_code' => $data['activation_code'] ?? 0,
                'is_blocked' => false,
                'first_seen' => $now,
                'last_seen' => $now,
                'heartbeat_count' => 1,
            ]);

            DeviceHistory::record('registered', $device->device_id, $device->name,
                $device->revendeur, 'Nouveau device enregistré depuis l\'application.', $request->ip());

            return response()->json([
                'status' => 'active',
                'action' => 'registered',
                'message' => 'Device enregistré avec succès.',
            ]);
        }

        $wasBlocked = $device->is_blocked;

        $device->update([
            'last_seen' => $now,
            'heartbeat_count' => $device->heartbeat_count + 1,
            'revendeur' => $data['revendeur'] ?? $device->revendeur,
            'app_version' => $data['app_version'] ?? $device->app_version,
            'package_name' => $data['package_name'] ?? $device->package_name,
            'app_name' => $data['app_name'] ?? $device->app_name,
            'activation_code' => $data['activation_code'] ?? $device->activation_code,
        ]);

        DeviceHistory::record('connected', $device->device_id, $device->name,
            $device->revendeur,
            $wasBlocked
                ? 'Connexion refusée : device bloqué (mode évaluation).'
                : 'Connexion signalée par l\'application.',
            $request->ip());

        return response()->json([
            'status' => $device->is_blocked ? 'blocked' : 'active',
            'action' => 'heartbeat',
            'message' => $device->is_blocked
                ? 'Ce device est bloqué. Basculement en mode évaluation.'
                : 'Device actif.',
        ]);
    }

    /**
     * Signale que l'application n'est plus au premier plan (arriere-plan /
     * fermeture). Met a jour last_disconnect afin que l'interface web bascule
     * le device hors ligne immediatement.
     */
    public function disconnect(Request $request)
    {
        $data = $request->validate([
            'device_id' => ['required', 'string', 'max:100'],
        ]);

        $device = Device::where('device_id', $data['device_id'])->first();

        if ($device) {
            $device->update(['last_disconnect' => now()]);

            DeviceHistory::record('disconnected', $device->device_id, $device->name,
                $device->revendeur,
                'L\'application a signalé son passage en arrière-plan.',
                $request->ip());
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Déconnexion signalée.',
        ]);
    }

    /**
     * Ajoute le revendeur a la liste s'il n'y existe pas encore.
     */
    private function registerRevendeur(?string $name): void
    {
        if ($name === null) {
            return;
        }

        $name = trim($name);
        if ($name === '') {
            return;
        }

        Revendeur::firstOrCreate(['name' => $name]);
    }
}
