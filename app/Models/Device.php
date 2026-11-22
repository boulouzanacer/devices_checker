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
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
        'last_disconnect' => 'datetime',
        'heartbeat_count' => 'integer',
    ];

    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }
}
