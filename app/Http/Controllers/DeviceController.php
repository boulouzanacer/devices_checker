<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceHistory;
use App\Models\Revendeur;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = Device::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('device_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($revendeur = $request->input('revendeur')) {
            $query->where('revendeur', $revendeur);
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_blocked', false);
            } elseif ($status === 'blocked') {
                $query->where('is_blocked', true);
            }
        }

        $devices = $query->orderByDesc('last_seen')->paginate(15)->withQueryString();
        $total = Device::count();
        $revendeurs = Revendeur::orderBy('name')->pluck('name');

        return view('devices.index', compact('devices', 'total', 'revendeurs'));
    }

    public function create()
    {
        return view('devices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => ['required', 'string', 'max:100', 'unique:devices,device_id'],
            'name' => ['nullable', 'string', 'max:100'],
            'revendeur' => ['nullable', 'string', 'max:100'],
        ]);

        $data['is_blocked'] = false;
        $data['first_seen'] = now();
        $data['last_seen'] = now();

        Device::create($data);
        $this->registerRevendeur($data['revendeur'] ?? null);

        DeviceHistory::record('registered', $data['device_id'], $data['name'] ?? null,
            $data['revendeur'] ?? null, 'Device ajouté depuis l\'interface.');

        return redirect()->route('devices.index')->with('success', 'Device ajouté.');
    }

    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'revendeur' => ['nullable', 'string', 'max:100'],
        ]);

        $device->update($data);
        $this->registerRevendeur($data['revendeur'] ?? null);

        DeviceHistory::record('updated', $device->device_id, $device->name,
            $device->revendeur, 'Device modifié depuis l\'interface.');

        return redirect()->route('devices.index')->with('success', 'Device mis à jour.');
    }

    public function destroy(Device $device)
    {
        DeviceHistory::record('deleted', $device->device_id, $device->name,
            $device->revendeur, 'Device supprimé depuis l\'interface.');

        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device supprimé.');
    }

    public function toggleBlock(Device $device)
    {
        $device->update([
            'is_blocked' => !$device->is_blocked,
            'last_disconnect' => !$device->is_blocked ? now() : null,
        ]);

        if ($device->is_blocked) {
            DeviceHistory::record('disconnected', $device->device_id, $device->name,
                $device->revendeur, 'Device bloqué depuis l\'interface.');
        } else {
            DeviceHistory::record('connected', $device->device_id, $device->name,
                $device->revendeur, 'Device débloqué depuis l\'interface.');
        }

        return back()->with('success', $device->is_blocked ? 'Device bloqué.' : 'Device débloqué.');
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
