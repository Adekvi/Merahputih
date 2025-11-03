<?php

namespace App\Http\Controllers\Admin\Master\Wilayah;

use App\Http\Controllers\Controller;
use App\Models\Wilayah\Kelurahan;
use App\Models\WilayahLocation;
use Illuminate\Http\Request;

class WilayahLocationController extends Controller
{
     public function index()
    {
        $locations = WilayahLocation::with('kelurahan')->paginate(10);
        return view('master.wilayah_locations.index', compact('locations'));
    }

    public function create()
    {
        $kelurahans = Kelurahan::all();
        return view('master.wilayah_locations.create', compact('kelurahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'id_kecamatan' => 'nullable|integer',
            'nama_kelurahan' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'provider' => 'nullable|string|max:50',
            'raw_response' => 'nullable|string',
        ]);

        WilayahLocation::create($request->all());

        return redirect()->route('wilayah_locations.index')->with('success', 'Data lokasi berhasil ditambahkan.');
    }

    public function edit(WilayahLocation $wilayah_location)
    {
        $kelurahans = Kelurahan::all();
        return view('master.wilayah_locations.edit', compact('wilayah_location', 'kelurahans'));
    }

    public function update(Request $request, WilayahLocation $wilayah_location)
    {
        $request->validate([
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'id_kecamatan' => 'nullable|integer',
            'nama_kelurahan' => 'nullable|string|max:191',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'provider' => 'nullable|string|max:50',
            'raw_response' => 'nullable|string',
        ]);

        $wilayah_location->update($request->all());

        return redirect()->route('wilayah_locations.index')->with('success', 'Data lokasi berhasil diupdate.');
    }

    public function destroy(WilayahLocation $wilayah_location)
    {
        $wilayah_location->delete();
        return redirect()->route('wilayah_locations.index')->with('success', 'Data lokasi berhasil dihapus.');
    }
}
