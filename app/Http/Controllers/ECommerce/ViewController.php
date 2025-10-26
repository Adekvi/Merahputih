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
        $search       = $request->input('search');
        $entries      = $request->input('entries', 10);
        $id_kecamatan = $request->input('id_kecamatan');
        $id_kelurahan = $request->input('id_kelurahan');
        $type  = $request->input('type');

        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        $query = Product::query()
            ->with(['kecamatan', 'kelurahan', 'suply', 'demand', 'satuanJumlah', 'satuanHarga'])
            ->when($type === 'supply', function ($q) use ($id_kecamatan, $id_kelurahan) {
                $q->whereHas('suply', function ($sub) use ($id_kecamatan, $id_kelurahan) {
                    $sub->where('status', 'posting');
                    if ($id_kecamatan) $sub->where('id_kecamatan', $id_kecamatan);
                    if ($id_kelurahan) $sub->where('id_kelurahan', $id_kelurahan);
                });
            })
            ->when($type === 'demand', function ($q) use ($id_kecamatan, $id_kelurahan) {
                $q->whereHas('demand', function ($sub) use ($id_kecamatan, $id_kelurahan) {
                    $sub->where('status', 'posting');
                    if ($id_kecamatan) $sub->where('id_kecamatan', $id_kecamatan);
                    if ($id_kelurahan) $sub->where('id_kelurahan', $id_kelurahan);
                });
            })
            ->when(!$type, function ($q) use ($id_kecamatan, $id_kelurahan) {
                $q->where(function ($sub) use ($id_kecamatan, $id_kelurahan) {
                    $sub->whereHas('suply', function ($s) use ($id_kecamatan, $id_kelurahan) {
                        $s->where('status', 'posting');
                        if ($id_kecamatan) $s->where('id_kecamatan', $id_kecamatan);
                        if ($id_kelurahan) $s->where('id_kelurahan', $id_kelurahan);
                    })
                    ->orWhereHas('demand', function ($s) use ($id_kecamatan, $id_kelurahan) {
                        $s->where('status', 'posting');
                        if ($id_kecamatan) $s->where('id_kecamatan', $id_kecamatan);
                        if ($id_kelurahan) $s->where('id_kelurahan', $id_kelurahan);
                    });
                });
            })
        ->orderBy('id', 'desc');

        if ($type !== 'supply' && $type !== 'demand') {
            if ($id_kecamatan) {
                $query->where('id_kecamatan', $id_kecamatan);
            }
            if ($id_kelurahan) {
                $query->where('id_kelurahan', $id_kelurahan);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search, $type) {
                if ($type === 'supply') {
                    $q->whereHas('suply', fn($sub) => $this->applySearchConditions($sub, $search, 'supply'));
                } elseif ($type === 'demand') {
                    $q->whereHas('demand', fn($sub) => $this->applySearchConditions($sub, $search, 'demand'));
                } else {
                    $q->whereHas('suply', fn($sub) => $this->applySearchConditions($sub, $search, 'supply'))
                    ->orWhereHas('demand', fn($sub) => $this->applySearchConditions($sub, $search, 'demand'));
                }
            });
        }

        $market = $query->orderBy('id', 'desc')->paginate($entries);
        $market->appends($request->query());

        $kelurahan = collect();
        if ($id_kecamatan) {
            $kelurahan = Kelurahan::where('id_kecamatan', $id_kecamatan)
                ->orderBy('nama_kelurahan')
                ->get();
        }

        return view('e-commerce.index', compact(
            'market', 'search', 'entries', 'kecamatan', 'kelurahan',
            'id_kecamatan', 'id_kelurahan', 'type'
        ));
    }

    private function applySearchConditions($query, $search, $type)
    {
        return $query->where(function ($sub) use ($search, $type) {
            $sub->where('nama_barang', 'LIKE', "%{$search}%")
                ->orWhere('kategori', 'LIKE', "%{$search}%")
                ->orWhere('jumlah', 'LIKE', "%{$search}%")
                ->orWhere('harga', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->orWhere('no_hp', 'LIKE', "%{$search}%")
                ->orWhereHas('kecamatan', fn($sq) => $sq->where('nama_kecamatan', 'LIKE', "%{$search}%"))
                ->orWhereHas('kelurahan', fn($sq) => $sq->where('nama_kelurahan', 'LIKE', "%{$search}%"));

            if ($type === 'supply') {
                $sub->orWhere('nama_supplier', 'LIKE', "%{$search}%");
            }
        });
    }
}
