<?php

namespace App\Http\Controllers\Admin\Master\Wilayah;

use App\Http\Controllers\Controller;
use App\Models\Wilayah\Kabupaten;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::with('kabupaten')->latest()->paginate(10);
        return view('master.kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        $kabupatens = Kabupaten::all();
        return view('master.kecamatan.create', compact('kabupatens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kabupaten' => 'required|exists:kabupatens,id',
            'nama_kecamatan' => 'required|string|max:255',
            'status' => 'nullable|string|max:100',
        ]);

        Kecamatan::create($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        $kabupatens = Kabupaten::all();
        return view('master.kecamatan.edit', compact('kecamatan', 'kabupatens'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'id_kabupaten' => 'required|exists:kabupatens,id',
            'nama_kecamatan' => 'required|string|max:255',
            'status' => 'nullable|string|max:100',
        ]);

        $kecamatan->update($request->all());

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil dihapus.');
    }
}
