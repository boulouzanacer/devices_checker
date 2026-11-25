<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'revendeur',
        'is_blocked',
        'first_seen',
        'last_seen',
        'last_disconnect',
        'heartbeat_count',
        'app_version',
        'package_name',
        'app_name',
        'activation_code',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
        'last_disconnect' => 'datetime',
        'heartbeat_count' => 'integer',
        'activation_code' => 'integer',
    ];

    /**
     * Delai (en minutes) au-dela duquel un device est considere hors ligne.
     * Aligne sur le heartbeat mobile (45 s) : la marge de 1 min evite tout
     * clignotement entre deux heartbeats.
     */
    public const ONLINE_WINDOW_MINUTES = 1;

    /**
     * Device "en ligne" si :
     *  - son dernier contact (last_seen) date de moins de ONLINE_WINDOW_MINUTES, ET
     *  - aucun signal de sortie (last_disconnect) n'est posterieur a ce dernier
     *    contact : dans ce cas l'application a signale son arret et le device est
     *    bascule hors ligne immediatement.
     */
    public function getIsOnlineAttribute(): bool
    {
        if ($this->last_seen === null) {
            return false;
        }

        if ($this->last_disconnect !== null
            && $this->last_disconnect->greaterThanOrEqualTo($this->last_seen)) {
            return false;
        }

        return $this->last_seen->greaterThan(now()->subMinutes(self::ONLINE_WINDOW_MINUTES));
    }

    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }
}
