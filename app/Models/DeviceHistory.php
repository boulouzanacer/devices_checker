<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'device_name',
        'revendeur',
        'event',
        'description',
        'ip_address',
    ];

    /**
     * Types d'evenements journalises et leur libelle affiche.
     */
    public const EVENTS = [
        'registered' => 'Inscription',
        'connected' => 'Connexion',
        'disconnected' => 'Déconnexion',
        'blocked' => 'Bloqué',
        'unblocked' => 'Débloqué',
        'updated' => 'Modification',
        'deleted' => 'Suppression',
    ];

    /** Libelle lisible de l'evenement. */
    public function getEventLabelAttribute(): string
    {
        return self::EVENTS[$this->event] ?? $this->event;
    }

    /**
     * Enregistre un evenement dans l'historique.
     */
    public static function record(
        string $event,
        ?string $deviceId,
        ?string $deviceName = null,
        ?string $revendeur = null,
        ?string $description = null,
        ?string $ip = null
    ): void {
        static::create([
            'event' => $event,
            'device_id' => $deviceId,
            'device_name' => $deviceName,
            'revendeur' => $revendeur,
            'description' => $description,
            'ip_address' => $ip,
        ]);
    }
}
