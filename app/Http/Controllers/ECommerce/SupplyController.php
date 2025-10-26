<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use App\Models\ECommerce\Supply;
use App\Models\User;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $id_kecamatan = $request->input('id_kecamatan');
        $id_kelurahan = $request->input('id_kelurahan');

        // Ambil semua kecamatan
        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        // Query supply
        $query = Supply::with(['satuanJumlah', 'satuanHarga', 'kecamatan', 'kelurahan'])->orderBy('id', 'desc');

        // Filter berdasarkan kecamatan
        if ($id_kecamatan) {
            $query->where('id_kecamatan', $id_kecamatan);
        }

        // Filter berdasarkan kelurahan
        if ($id_kelurahan) {
            $query->where('id_kelurahan', $id_kelurahan);
        }

        // Pencarian global
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_supplier', 'LIKE', "%{$search}%")
                    ->orWhere('nama_barang', 'LIKE', "%{$search}%")
                    ->orWhere('kategori', 'LIKE', "%{$search}%")
                    ->orWhere('jumlah', 'LIKE', "%{$search}%")
                    ->orWhere('harga', 'LIKE', "%{$search}%")
                    ->orWhere('no_hp', 'LIKE', "%{$search}%")
                    ->orWhereHas('kecamatan', fn($q2) => $q2->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                    ->orWhereHas('kelurahan', fn($q2) => $q2->where('nama_kelurahan', 'LIKE', "%{$search}%"));
            });
        }

        $suply = $query->paginate($entries);
        $suply->appends($request->query());

        // Ambil kelurahan berdasarkan kecamatan yang dipilih (untuk dropdown)
        $kelurahan = collect();
        if ($id_kecamatan) {
            $kelurahan = Kelurahan::where('id_kecamatan', $id_kecamatan)
                ->orderBy('nama_kelurahan')
                ->get();
        }

        // dd($suply);

        return view('e-commerce.supply.index', compact(
            'suply',
            'search',
            'entries',
            'kecamatan',
            'kelurahan',
            'id_kecamatan',
            'id_kelurahan'
        ));
    }

    public function getKelurahan(Request $request)
    {
        $kecamatanId = $request->input('id_kecamatan');
        $kelurahans = Kelurahan::where('id_kecamatan', $kecamatanId)->get();
        return response()->json($kelurahans);
    }

    public function posting(Request $request, $id)
    {
        $suply = Supply::findOrFail($id);

        // Update status menjadi posting
        $suply->status = 'posting';
        $suply->save();

        // Cek apakah sudah ada di tabel products
        $exists = Product::where('supply_id', $suply->id)->exists();

        if (!$exists) {
            Product::create([
                'user_id'          => $suply->user_id ?? Auth::user()->id, // sesuaikan
                'id_kecamatan'     => $suply->id_kecamatan,
                'id_kelurahan'     => $suply->id_kelurahan,
                'supply_id'        => $suply->id,
                'demand_id'        => null,
                'nama_barang'      => $suply->nama_barang,
                'kategori'         => $suply->kategori,
                'jumlah'           => $suply->jumlah,
                'satuan_jumlah_id' => $suply->satuan_jumlah_id,
                'harga'            => $suply->harga,
                'satuan_harga_id'  => $suply->satuan_harga_id,
                'status'           => 'posting',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditawarkan dan muncul di Market!'
        ]);
    }

    public function create()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();

        return view('e-commerce.supply.create', compact('user', 'kecamatan', 'kelurahan'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'id_kecamatan' => 'exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'nama_supplier' => 'string|max:255',
            'nama_barang' => 'string|max:255',
            'kategori' => 'string|max:255',
            'tgl_sup' => 'date',
            'jumlah' => 'integer|min:1',
            'satuan_jumlah_id' => 'nullable|exists:satuans,id',
            'harga' => 'numeric|min:0',
            'satuan_harga_id' => 'nullable|exists:satuans,id',
            'alamat' => 'string|max:500',
            'no_hp' => 'string|max:20',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $originalName = $request->file('gambar')->getClientOriginalName();
            $gambar = $request->file('gambar')->storeAs('ecomerce/suply', $originalName, 'public');
        }

        $data = [
            'id_kecamatan' => $request->input('id_kecamatan'),
            'id_kelurahan' => $request->input('id_kelurahan'),
            'nama_supplier' => $request->input('nama_supplier'),
            'nama_barang' => $request->input('nama_barang'),
            'kategori' => $request->input('kategori'),
            'tgl_sup' => $request->input('tgl_sup'),
            'jumlah' => $request->input('jumlah'),
            'satuan_jumlah_id' => $request->input('satuan_jumlah_id'),
            'harga' => $request->input('harga'),
            'satuan_harga_id' => $request->input('satuan_harga_id'),
            'satuan' => $request->input('satuan'),
            'no_hp' => $request->input('no_hp'),
            'alamat' => $request->input('alamat'),
            'gambar' => $gambar,
            'status' => 'suply',
        ];

        Supply::create($data);

        return redirect()->route('e-commerce.supply')->with('success', 'Supply created successfully.');
    }

    public function edit($id)
    {
        $supply = Supply::findOrFail($id);
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();

        return view('e-commerce.supply.edit', compact('supply', 'kecamatan', 'kelurahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'nama_supplier' => 'string|max:255',
            'nama_barang' => 'string|max:255',
            'kategori' => 'string|max:255',
            'tgl_sup' => 'date',
            'jumlah' => 'integer|min:1',
            'satuan_jumlah_id' => 'nullable|exists:satuans,id',
            'harga' => 'numeric|min:0',
            'satuan_harga_id' => 'nullable|exists:satuans,id',
            'alamat' => 'string|max:500',
            'no_hp' => 'string|max:20',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $supply = Supply::findOrFail($id);

        $gambar = $supply->gambar;

        if ($request->hasFile('gambar')) {
            if ($gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }

            $originalName = $request->file('gambar')->getClientOriginalName();
            $gambar = $request->file('gambar')->storeAs('ecomerce/suply', $originalName, 'public');
        }

        $data = [
            'id_kecamatan' => $request->input('id_kecamatan'),
            'id_kelurahan' => $request->input('id_kelurahan'),
            'nama_supplier' => $request->input('nama_supplier'),
            'nama_barang' => $request->input('nama_barang'),
            'kategori' => $request->input('kategori'),
            'tgl_sup' => $request->input('tgl_sup'),
            'jumlah' => $request->input('jumlah'),
            'satuan_jumlah_id' => $request->input('satuan_jumlah_id'),
            'harga' => $request->input('harga'),
            'satuan_harga_id' => $request->input('satuan_harga_id'),
            'satuan' => $request->input('satuan'),
            'no_hp' => $request->input('no_hp'),
            'alamat' => $request->input('alamat'),
            'gambar' => $gambar,
            'status' => 'posting',
        ];

        $supply->update($data);

        return redirect()->route('e-commerce.supply')->with('success', 'Supply updated successfully.');
    }

    public function delete($id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();

        return redirect()->route('e-commerce.supply')->with('success', 'Supply deleted successfully.');
    }
}
