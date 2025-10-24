<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Parcel;
use App\Models\ParcelCrop;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $kecamatans = Survey::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        return view('stats.index', compact('kecamatans'));
    }

    // API: ambil desa berdasarkan kecamatan
    public function getDesas(Request $req)
    {
        $kecamatan = $req->query('kecamatan');
        if (!$kecamatan) {
            return response()->json(['error' => 'kecamatan required'], 422);
        }

        $desas = Survey::where('kecamatan', $kecamatan)
            ->select('desa')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa');

        return response()->json($desas);
    }

    // API: ambil jenis potensi yang tersedia di desa (parcels.type)
    public function getJenisPotensi(Request $req)
    {
        $kecamatan = $req->query('kecamatan');
        $desa = $req->query('desa');

        $query = Parcel::query()
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->when($desa, fn($q) => $q->where('surveys.desa', $desa))
            ->select('parcels.type')
            ->distinct()
            ->pluck('type');

        return response()->json($query);
    }

    // API: ringkasan produksi per kec/desa/tipe (sama seperti versi awal)
    public function getProduksi(Request $req)
    {
        $kecamatan = $req->query('kecamatan');
        $desa = $req->query('desa');
        $type = $req->query('type');

        if (!$kecamatan || !$desa) {
            return response()->json(['error' => 'kecamatan dan desa diperlukan'], 422);
        }

        // === QUERY PERTANIAN (CROPS) ===
        $cropQuery = ParcelCrop::query()
            ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->when($desa, fn($q) => $q->where('surveys.desa', $desa))
            ->when($type, fn($q) => $q->where('parcels.type', $type))
            ->groupBy('parcels.type', 'parcel_crops.nama_tanaman', 'parcel_crops.catatan')
            ->selectRaw('
            parcels.type as type,
            parcel_crops.nama_tanaman as nama,
            SUM(COALESCE(parcel_crops.luas_hektare,0)) as luas_sum,
            SUM(COALESCE(parcel_crops.produksi_ton,0)) as produksi_sum,
            MIN(parcel_crops.catatan) as catatan
        ')
            ->get();

        // === QUERY PETERNAKAN (LIVESTOCKS) ===
        $livestockQuery = Livestock::query()
            ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->when($desa, fn($q) => $q->where('surveys.desa', $desa))
            ->when($type, fn($q) => $q->where('parcels.type', $type))
            ->groupBy('parcels.type', 'livestocks.jenis_ternak', 'livestocks.produksi')
            ->selectRaw('
            parcels.type as type,
            livestocks.jenis_ternak as nama,
            SUM(COALESCE(livestocks.jumlah,0)) as jumlah_sum,
            MIN(livestocks.produksi) as produksi_note
        ')
            ->get();

        // === GABUNG HASIL ===
        $result = [];

        foreach ($cropQuery as $r) {
            $result[$r->type]['crops'][] = [
                'nama' => $r->nama,
                'luas' => (float) $r->luas_sum,
                'produksi' => (float) $r->produksi_sum,
                'catatan' => $r->catatan,
            ];
        }

        foreach ($livestockQuery as $r) {
            $result[$r->type]['livestocks'][] = [
                'nama' => $r->nama,
                'jumlah' => (int) $r->jumlah_sum,
                'produksi_note' => $r->produksi_note,
            ];
        }

        // === FORMAT AKHIR ===
        $final = [];
        foreach ($result as $typeKey => $data) {
            $final[] = [
                'type' => $typeKey,
                'crops' => $data['crops'] ?? [],
                'livestocks' => $data['livestocks'] ?? [],
            ];
        }

        return response()->json($final);
    }


    // API: ambil daftar komoditas/jenis berdasarkan pilihan "jenis potensi"
    // Endpoint: /api/komoditas?jenis={jenis}&kecamatan={}&desa={}
   public function getAllKomoditas(Request $req)
{
    $jenis = $req->query('jenis'); // contoh nilai: 'persawahan','perkebunan','livestocks','tambak','komoditas_lain','crops'
    $kecamatan = $req->query('kecamatan');
    $desa = $req->query('desa');

    if (!$jenis) {
        return response()->json([]);
    }

    // Jika jenis = livestocks -> ambil dari tabel livestocks
    if ($jenis === 'livestocks') {
        $rows = Livestock::query()
            ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->when($desa, fn($q) => $q->where('surveys.desa', $desa))
            ->selectRaw('DISTINCT livestocks.jenis_ternak as nama')
            ->orderBy('nama')
            ->pluck('nama');
    } else {
        // map jenis ke parcel.type dengan explicit mapping
        // frontend harus mengirim 'persawahan' atau 'perkebunan' untuk masing-masing
        $types = null;
        if ($jenis === 'persawahan') {
            $types = ['persawahan'];
        } elseif ($jenis === 'perkebunan') {
            $types = ['perkebunan'];
        } elseif ($jenis === 'tambak') {
            $types = ['tambak'];
        } elseif ($jenis === 'komoditas_lain') {
            $types = ['komoditas_lain'];
        } elseif ($jenis === 'crops') {
            // legacy / broad category: hanya persawahan (sebelumnya Anda gabung kebun — ubah sesuai kebutuhan)
            $types = ['persawahan'];
        } else {
            // fallback: cari di semua parcel_crops
            $types = null;
        }

        $q = ParcelCrop::query()
            ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->when($types, fn($q) => $q->whereIn('parcels.type', $types))
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->when($desa, fn($q) => $q->where('surveys.desa', $desa))
            ->selectRaw('DISTINCT parcel_crops.nama_tanaman as nama')
            ->orderBy('nama');

        $rows = $q->pluck('nama');
    }

    return response()->json($rows->values());
}

// API: ambil daftar desa yang menghasilkan komoditas tertentu
public function getDesaByKomoditas(Request $req)
{
    $jenis = $req->query('jenis');
    $komoditas = $req->query('komoditas');
    $kecamatan = $req->query('kecamatan');

    if (!$jenis || !$komoditas) {
        return response()->json(['error' => 'jenis and komoditas required'], 422);
    }

    if ($jenis === 'livestocks') {
        $q = Livestock::query()
            ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->where('livestocks.jenis_ternak', $komoditas)
            ->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
            ->selectRaw('surveys.kecamatan, surveys.desa, SUM(COALESCE(livestocks.jumlah,0)) as jumlah_sum, MIN(livestocks.produksi) as produksi_note')
            ->groupBy('surveys.kecamatan', 'surveys.desa')
            ->orderBy('surveys.kecamatan')
            ->get();

        $areas = $q->map(fn($r) => [
            'kecamatan' => $r->kecamatan,
            'desa' => $r->desa,
            'jumlah' => (int) $r->jumlah_sum,
            'produksi_note' => $r->produksi_note,
        ]);
    } else {
        // Untuk tanaman/komoditas: handle jenis eksplisit (persawahan / perkebunan / tambak / komoditas_lain / crops)
        $q = ParcelCrop::query()
            ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
            ->where('parcel_crops.nama_tanaman', $komoditas);

        // mapping jenis => filter parcels.type
        if (in_array($jenis, ['persawahan','perkebunan','tambak','komoditas_lain'])) {
            $q->where('parcels.type', $jenis);
        } elseif ($jenis === 'crops') {
            // legacy: treat as persawahan (ubah bila mau gabungkan keduanya)
            $q->whereIn('parcels.type', ['persawahan']);
        } // else: no additional parcel.type filter

        $q = $q->when($kecamatan, fn($q) => $q->where('surveys.kecamatan', $kecamatan))
               ->selectRaw('surveys.kecamatan, surveys.desa, SUM(COALESCE(parcel_crops.luas_hektare,0)) as luas_sum, SUM(COALESCE(parcel_crops.produksi_ton,0)) as produksi_sum, MIN(parcel_crops.catatan) as catatan')
               ->groupBy('surveys.kecamatan', 'surveys.desa')
               ->orderBy('surveys.kecamatan')
               ->get();

        $areas = $q->map(fn($r) => [
            'kecamatan' => $r->kecamatan,
            'desa' => $r->desa,
            'luas' => (float) $r->luas_sum,
            'produksi' => (float) $r->produksi_sum,
            'catatan' => $r->catatan,
        ]);
    }

    return response()->json($areas->values());
}
}
