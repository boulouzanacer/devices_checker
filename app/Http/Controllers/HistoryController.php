<?php

namespace App\Http\Controllers;

use App\Models\DeviceHistory;
use App\Models\Revendeur;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = DeviceHistory::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('device_id', 'like', "%{$search}%")
                    ->orWhere('device_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($event = $request->input('event')) {
            $query->where('event', $event);
        }

        if ($revendeur = $request->input('revendeur')) {
            $query->where('revendeur', $revendeur);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $histories = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $total = DeviceHistory::count();
        $revendeurs = Revendeur::orderBy('name')->pluck('name');
        $events = DeviceHistory::EVENTS;

        return view('history.index', compact('histories', 'total', 'revendeurs', 'events'));
    }
}
