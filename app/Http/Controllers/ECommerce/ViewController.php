<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);
        $id_kecamatan = $request->input('id_kecamatan');
        $id_kelurahan = $request->input('id_kelurahan');

        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        $query = Product::with(['kecamatan', 'kelurahan', 'suply', 'demand'])
            ->whereHas('suply', function ($q) {
                $q->where('status', 'posting');
            })
            ->orWhereHas('demand', function ($q) {
                $q->where('status', 'posting');
            })
            ->orderBy('id', 'desc');

        if ($id_kecamatan) {
            $query->where('id_kecamatan', $id_kecamatan);
        }

        if ($id_kelurahan) {
            $query->where('id_kelurahan', $id_kelurahan);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('suply', function ($sub) use ($search) {
                    $sub->where('nama_supplier', 'LIKE', "%{$search}%")
                        ->orWhere('nama_barang', 'LIKE', "%{$search}%")
                        ->orWhere('kategori', 'LIKE', "%{$search}%")
                        ->orWhere('jumlah', 'LIKE', "%{$search}%")
                        ->orWhere('harga', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%")
                        ->orWhere('no_hp', 'LIKE', "%{$search}%")
                        ->orWhereHas('kecamatan', fn($q2) => $q2->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                        ->orWhereHas('kelurahan', fn($q2) => $q2->where('nama_kelurahan', 'LIKE', "%{$search}%"));
                })
                ->orWhereHas('demand', function ($sub) use ($search) {
                    $sub->where('nama_barang', 'LIKE', "%{$search}%")
                        ->orWhere('kategori', 'LIKE', "%{$search}%")
                        ->orWhere('jumlah', 'LIKE', "%{$search}%")
                        ->orWhere('harga', 'LIKE', "%{$search}%")
                        ->orWhere('alamat', 'LIKE', "%{$search}%")
                        ->orWhere('no_hp', 'LIKE', "%{$search}%")
                        ->orWhereHas('kecamatan', fn($q2) => $q2->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                        ->orWhereHas('kelurahan', fn($q2) => $q2->where('nama_kelurahan', 'LIKE', "%{$search}%"));
                });
            });
        }

        $market = $query->paginate($entries);
        $market->appends($request->query());

        // Ambil kelurahan berdasarkan kecamatan yang dipilih (untuk dropdown)
        $kelurahan = collect();
        if ($id_kecamatan) {
            $kelurahan = Kelurahan::where('id_kecamatan', $id_kecamatan)
                ->orderBy('nama_kelurahan')
                ->get();
        }

        // dd($market);

        return view('e-commerce.index', compact(
            'market',
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
}
