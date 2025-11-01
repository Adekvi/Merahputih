<?php

namespace App\Http\Controllers\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\Barter;
use App\Models\ECommerce\Demand;
use App\Models\Ecommerce\Product;
use App\Models\ECommerce\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarterController extends Controller
{
    public function getBarterOptions(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type'); // nilai: 'suply' atau 'demand'

        if ($type === 'demand') {
            // Jika user klik data permintaan, ambil semua barang SUPPLY milik user saat ini
            $data = Supply::with(['user', 'kecamatan', 'kelurahan'])
                ->where('user_id', $user->id)
                ->select('id', 'nama_barang', 'jumlah', 'harga', 'gambar', 'id_kecamatan', 'id_kelurahan', 'user_id')
                ->orderBy('nama_barang')
                ->get()
                ->map(function ($item) {
                    $kecamatan = $item->kecamatan;
                    $kelurahan = $item->kelurahan;

                    // Jika kecamatan null, tapi kelurahan ada → ambil kecamatan dari kelurahan
                    if (!$kecamatan && $kelurahan) {
                        $kecamatan = $kelurahan->kecamatan;
                    }

                    return [
                        'id' => $item->id,
                        'nama_barang' => $item->nama_barang,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                        'gambar' => $item->gambar,
                        'nama_kecamatan' => optional($kecamatan)->nama_kecamatan ?? '-',
                        'nama_kelurahan' => optional($kelurahan)->nama_kelurahan ?? '-',
                        'pemilik' => optional($item->user)->name,
                    ];
                });
        } elseif ($type === 'suply') {
            // Jika user klik data penawaran, ambil semua barang DEMAND milik user saat ini
            $data = Demand::with(['user', 'kecamatan', 'kelurahan'])
                ->where('user_id', $user->id)
                ->select('id', 'nama_barang', 'jumlah', 'harga', 'gambar', 'id_kecamatan', 'id_kelurahan', 'user_id')
                ->orderBy('nama_barang')
                ->get()
                ->map(function ($item) {
                    $kecamatan = $item->kecamatan;
                    $kelurahan = $item->kelurahan;

                    // Jika kecamatan null, tapi kelurahan ada → ambil kecamatan dari kelurahan
                    if (!$kecamatan && $kelurahan) {
                        $kecamatan = $kelurahan->kecamatan;
                    }

                    return [
                        'id' => $item->id,
                        'nama_barang' => $item->nama_barang,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                        'gambar' => $item->gambar,
                        'nama_kecamatan' => optional($kecamatan)->nama_kecamatan ?? '-',
                        'nama_kelurahan' => optional($kelurahan)->nama_kelurahan ?? '-',
                        'pemilik' => optional($item->user)->name,
                    ];
                });
        } else {
            return response()->json(['error' => 'Type tidak dikenali'], 400);
        }
        // dd($data);

        return response()->json($data);
    }


    public function store(Request $request, Product $product)
    {
        $user = Auth::user();

        // 1. Cek produk bukan milik sendiri
        if ($product->user_id == $user->id) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa barter dengan produk sendiri!']);
        }

        // 2. Cek status produk target = posting
        if ($product->status !== 'posting') {
            return response()->json(['success' => false, 'message' => 'Produk ini sudah tidak tersedia untuk barter.']);
        }

        // 3. Ambil produk user yang akan ditawarkan
        $myProductId = $request->my_product_id;
        $myProduct = Product::where('user_id', $user->id)
                            ->where('id', $myProductId)
                            ->where('status', 'posting')
                            ->first();

        if (!$myProduct) {
            return response()->json(['success' => false, 'message' => 'Produk yang kamu tawarkan tidak valid atau sudah tidak tersedia.']);
        }

        // 4. Simpan barter (langsung accepted)
        Barter::create([
            'product_a_id' => $myProduct->id,
            'product_b_id' => $product->id,
            'user_a_id'    => $user->id,
            'user_b_id'    => $product->user_id,
            'status'       => 'accepted',
        ]);

        // 5. Update status kedua produk
        $myProduct->update(['status' => 'bartered']);
        $product->update(['status' => 'bartered']);

        return response()->json([
            'success' => true,
            'message' => 'Barter berhasil! Status produk telah diubah.'
        ]);
    }
}
