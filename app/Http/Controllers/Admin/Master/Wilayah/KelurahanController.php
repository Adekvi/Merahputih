<?php

namespace App\Http\Controllers\Admin\Master\Wilayah;

use App\Http\Controllers\Controller;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index()
    {
        $kelurahans = Kelurahan::with('kecamatan')->paginate(10);
        return view('master.kelurahan.index', compact('kelurahans'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('master.kelurahan.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required',
            'nama_kelurahan' => 'required|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        Kelurahan::create($request->all());
        return redirect()->route('kelurahan.index')->with('success', 'Data kelurahan berhasil ditambahkan.');
    }

    public function edit(Kelurahan $kelurahan)
    {
        $kecamatans = Kecamatan::all();
        return view('master.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'id_kecamatan' => 'required',
            'nama_kelurahan' => 'required|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $kelurahan->update($request->all());
        return redirect()->route('kelurahan.index')->with('success', 'Data kelurahan berhasil diperbarui.');
    }

    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();
        return redirect()->route('kelurahan.index')->with('success', 'Data kelurahan berhasil dihapus.');
    }
}
