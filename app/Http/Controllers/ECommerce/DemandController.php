<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\Demand;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default 10
        $page = $request->input('page', 1);

        // Query semua kabupaten
        $query = Demand::orderBy('id', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_alat', 'LIKE', "%{$search}%")
                    ->orWhere('nama_donatur', 'LIKE', "%{$search}%")
                    ->orWhere('jenis', 'LIKE', "%{$search}%")
                    ->orWhere('alamat', 'LIKE', "%{$search}%")
                    ->orWhere('nominal', 'LIKE', "%{$search}%")
                    ->orWhere('tgl', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        $demand = $query->orderBy('id', 'desc')->paginate($entries, ['*'], 'page', $page);
        $demand->appends(['search' => $search, 'entries' => $entries]);

        return view('e-commerce.demand.index', compact('demand', 'search', 'entries'));
    }
}
