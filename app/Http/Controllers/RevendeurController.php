<?php

namespace App\Http\Controllers;

use App\Models\Revendeur;

class RevendeurController extends Controller
{
    public function index()
    {
        $revendeurs = Revendeur::withCount('devices')
            ->orderBy('name')
            ->paginate(15);

        return view('revendeurs.index', compact('revendeurs'));
    }
}
