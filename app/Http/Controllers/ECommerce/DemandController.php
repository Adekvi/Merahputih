<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\Demand;
use App\Models\Ecommerce\Product;
use App\Models\ECommerce\Satuan;
use App\Models\User;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DemandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $id_kecamatan = $request->input('id_kecamatan');
        $id_kelurahan = $request->input('id_kelurahan');

        $user = Auth::user();

        // Ambil semua kecamatan
        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();
        $satuans = Satuan::all();

        // Query Demand
        $query = Demand::with([ 'satuanJumlah', 'satuanHarga', 'kecamatan', 'kelurahan'])
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc');

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
                    ->orWhere('jenis_barang', 'LIKE', "%{$search}%")
                    ->orWhere('jumlah', 'LIKE', "%{$search}%")
                    ->orWhere('satuan', 'LIKE', "%{$search}%")
                    ->orWhere('harga', 'LIKE', "%{$search}%")
                    ->orWhere('no_hp', 'LIKE', "%{$search}%")
                    ->orWhereHas('kecamatan', fn($q2) => $q2->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                    ->orWhereHas('kelurahan', fn($q2) => $q2->where('nama_kelurahan', 'LIKE', "%{$search}%"));
            });
        }

        $demand = $query->paginate($entries);
        $demand->appends($request->query());

        // Ambil kelurahan berdasarkan kecamatan yang dipilih (untuk dropdown)
        $kelurahan = collect();
        if ($id_kecamatan) {
            $kelurahan = Kelurahan::where('id_kecamatan', $id_kecamatan)
                ->orderBy('nama_kelurahan')
                ->get();
        }

        // dd($demand);

        return view('e-commerce.demand.index', compact(
            'demand',
            'search',
            'entries',
            'kecamatan',
            'kelurahan',
            'satuans',
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
        $request->validate([
            'nama_barang'       => 'required|string',
            'stok'              => 'required|numeric|min:1',
            'satuan_jumlah_id'  => 'required|exists:satuans,id',  // tambahkan
            'satuan_harga_id'   => 'required|exists:satuans,id',  // tambahkan
            'harga'             => 'required|numeric|min:0',
        ]);

        $demand = Demand::findOrFail($id);

        // Update data demand
        $demand->update([
            'nama_barang'      => $request->nama_barang,
            'jumlah'           => $request->stok,
            'satuan_jumlah_id' => $request->satuan_jumlah_id, // buat helper
            'harga'            => $request->harga,
            'satuan_harga_id' => $request->satuan_harga_id, // buat helper
            'status'           => 'posting',
        ]);

        // Simpan ke products jika belum ada
        $exists = Product::where('demand_id', $demand->id)->exists();

        if (!$exists) {
            Product::create([
                'user_id'          => $demand->user_id ?? Auth::user()->id,
                'id_kecamatan'     => $demand->id_kecamatan,
                'id_kelurahan'     => $demand->id_kelurahan,
                'supply_id'        => null,
                'demand_id'        => $demand->id,
                'nama_barang'      => $demand->nama_barang,
                'kategori'         => $demand->kategori,
                'jumlah'           => $demand->jumlah,
                'satuan_jumlah_id' => $demand->satuan_jumlah_id,
                'harga'            => $demand->harga,
                'satuan_harga_id'  => $demand->satuan_harga_id,
                'status'           => 'posting',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil diposting ke Market!'
        ]);
    }

    public function create()
    {
        $user = User::where('id', Auth::user()->id)->first();

        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();

        $satuan = Satuan::all();

        return view('e-commerce.demand.create', compact('user', 'kecamatan', 'kelurahan', 'satuan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_kecamatan' => 'exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'nama_demand' => 'string|max:255',
            'nama_barang' => 'string|max:255',
            'kategori' => 'string|max:255',
            'tgl_dem' => 'date',
            'jumlah' => 'integer|min:1',
            'satuan_jumlah_id' => 'nullable|exists:satuans,id',
            'harga' => 'numeric|min:0',
            'satuan_harga_id' => 'nullable|exists:satuans,id',
            'alamat' => 'string|max:500',
            'no_hp' => 'string|max:20',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);
        
        $userId = Auth::user()->id;

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $originalName = $request->file('gambar')->getClientOriginalName();
            $gambar = $request->file('gambar')->storeAs('ecomerce/demand', $originalName, 'public');
        }

        $data = [
            'user_id' => $userId,
            'id_kecamatan' => $request->input('id_kecamatan'),
            'id_kelurahan' => $request->input('id_kelurahan'),
            'nama_demand' => $request->input('nama_demand'),
            'nama_barang' => $request->input('nama_barang'),
            'kategori' => $request->input('kategori'),
            'tgl_dem' => $request->input('tgl_dem'),
            'jumlah' => $request->input('jumlah'),
            'satuan_jumlah_id' => $request->input('satuan_jumlah_id'),
            'harga' => $request->input('harga'),
            'satuan_harga_id' => $request->input('satuan_harga_id'),
            'no_hp' => $request->input('no_hp'),
            'alamat' => $request->input('alamat'),
            'gambar' => $gambar,
            'status' => 'request',
        ];

        Demand::create($data);

        return redirect()->route('e-commerce.demand')->with('success', 'Demand created successfully.');
    }

    public function edit($id)
    {
        $demand = Demand::findOrFail($id);
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();

        return view('e-commerce.demand.edit', compact('demand', 'kecamatan', 'kelurahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'exists:kecamatans,id',
            'id_kelurahan' => 'nullable|exists:kelurahans,id',
            'nama_demand' => 'string|max:255',
            'nama_barang' => 'string|max:255',
            'kategori' => 'string|max:255',
            'tgl_dem' => 'date',
            'jumlah' => 'integer|min:1',
            'satuan_jumlah_id' => 'nullable|exists:satuans,id',
            'harga' => 'numeric|min:0',
            'satuan_harga_id' => 'nullable|exists:satuans,id',
            'alamat' => 'string|max:500',
            'no_hp' => 'string|max:20',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $demand = Demand::findOrFail($id);

        $gambar = $demand->gambar;

        if ($request->hasFile('gambar')) {
            if ($gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }

            $originalName = $request->file('gambar')->getClientOriginalName();
            $gambar = $request->file('gambar')->storeAs('ecomerce/demand', $originalName, 'public');
        }

        $data = [
            'id_kecamatan' => $request->input('id_kecamatan'),
            'id_kelurahan' => $request->input('id_kelurahan'),
            'nama_demand' => $request->input('nama_demand'),
            'nama_barang' => $request->input('nama_barang'),
            'kategori' => $request->input('kategori'),
            'tgl_dem' => $request->input('tgl_dem'),
            'jumlah' => $request->input('jumlah'),
            'satuan_jumlah_id' => $request->input('satuan_jumlah_id'),
            'harga' => $request->input('harga'),
            'satuan_harga_id' => $request->input('satuan_harga_id'),
            'no_hp' => $request->input('no_hp'),
            'alamat' => $request->input('alamat'),
            'gambar' => $gambar,
            'status' => 'posting',
        ];

        $demand->update($data);

        return redirect()->route('e-commerce.demand')->with('success', 'Demand updated successfully.');
    }

    public function delete($id)
    {
        $demand = Demand::findOrFail($id);
        $demand->delete();

        return redirect()->route('e-commerce.demand')->with('success', 'Demand deleted successfully.');
    }
}
