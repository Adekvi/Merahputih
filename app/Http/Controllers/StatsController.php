<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Survey;
use App\Models\Parcel;
use App\Models\ParcelCrop;
use App\Models\Livestock;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        // ✅ ambil kecamatan
        if (Schema::hasTable('kecamatans') && Schema::hasColumn('kecamatans', 'nama_kecamatan')) {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->pluck('nama_kecamatan', 'id')->toArray();
        } else {
            $rows = [];
            if (Schema::hasTable('surveys') && Schema::hasColumn('surveys', 'kecamatan')) {
                $rows = Survey::select('kecamatan')
                    ->distinct()
                    ->orderBy('kecamatan')
                    ->pluck('kecamatan')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            }
            $kecamatans = [];
            foreach ($rows as $r) {
                $kecamatans[$r] = $r;
            }
        }

        // dd($kecamatans);

        return view('stats.index', compact('kecamatans'));
    }

    public function getDesas(Request $req)
    {
        $kec = $req->query('kecamatan');
        if (!$kec) return response()->json([], 200);

        $kecNorm = trim((string)$kec);

        // 1) Jika numeric → anggap ID
        if (is_numeric($kecNorm)) {
            $kecId = (int)$kecNorm;

            // Prioritas: kelurahans.id_kecamatan
            if (Schema::hasTable('kelurahans') && Schema::hasColumn('kelurahans', 'id_kecamatan')) {
                $rows = Kelurahan::where('id_kecamatan', $kecId)
                    ->orderBy('nama_kelurahan')
                    ->select('id', DB::raw('nama_kelurahan as nama'))
                    ->get()
                    ->map(fn($r) => ['id' => $r->id, 'nama' => (string)$r->nama])
                    ->values();
                return response()->json($rows);
            }

            // Fallback: surveys.kecamatan_id
            if (Schema::hasTable('surveys') && Schema::hasColumn('surveys', 'kecamatan_id') && Schema::hasColumn('surveys', 'desa')) {
                $rows = Survey::where('kecamatan_id', $kecId)
                    ->selectRaw('DISTINCT TRIM(desa) as nama')
                    ->orderByRaw('TRIM(desa)')
                    ->pluck('nama')
                    ->filter()
                    ->map(fn($d) => ['id' => null, 'nama' => ucwords(strtolower(trim($d)))])
                    ->values();
                return response()->json($rows);
            }
        }

        // 2) Jika string → cari nama kecamatan
        $kecLower = mb_strtolower($kecNorm);

        if (Schema::hasTable('kecamatans') && Schema::hasColumn('kecamatans', 'nama_kecamatan')) {
            $kModel = Kecamatan::whereRaw('LOWER(TRIM(nama_kecamatan)) = ?', [$kecLower])->first();
            if ($kModel) {
                $kecId = $kModel->id;

                // Prioritas: kelurahans
                if (Schema::hasTable('kelurahans') && Schema::hasColumn('kelurahans', 'id_kecamatan')) {
                    $rows = Kelurahan::where('id_kecamatan', $kecId)
                        ->orderBy('nama_kelurahan')
                        ->select('id', DB::raw('nama_kelurahan as nama'))
                        ->get()
                        ->map(fn($r) => ['id' => $r->id, 'nama' => (string)$r->nama])
                        ->values();
                    return response()->json($rows);
                }

                // Fallback: surveys.kecamatan_id
                if (Schema::hasTable('surveys') && Schema::hasColumn('surveys', 'kecamatan_id') && Schema::hasColumn('surveys', 'desa')) {
                    $rows = Survey::where('kecamatan_id', $kecId)
                        ->selectRaw('DISTINCT TRIM(desa) as nama')
                        ->orderByRaw('TRIM(desa)')
                        ->pluck('nama')
                        ->filter()
                        ->map(fn($d) => ['id' => null, 'nama' => ucwords(strtolower(trim($d)))])
                        ->values();
                    return response()->json($rows);
                }
            }
        }

        // 3) Final fallback: cari langsung di surveys.kecamatan (string)
        if (Schema::hasTable('surveys') && Schema::hasColumn('surveys', 'kecamatan') && Schema::hasColumn('surveys', 'desa')) {
            $rows = Survey::whereRaw('LOWER(TRIM(kecamatan)) = ?', [$kecLower])
                ->selectRaw('DISTINCT TRIM(desa) as nama')
                ->orderByRaw('TRIM(desa)')
                ->pluck('nama')
                ->filter()
                ->map(fn($d) => ['id' => null, 'nama' => ucwords(strtolower(trim($d)))])
                ->values();
            return response()->json($rows);
        }

        return response()->json([], 200);
    }

    /**
     * ✅ API: Ambil jenis potensi (type) tersedia
     */
    public function getJenisPotensi(Request $req)
    {
        $kec = $req->query('kecamatan');
        $desa = $req->query('desa');

        $q = Parcel::query()
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id');

        if ($kec) {
            $kec = trim((string)$kec);
            if (is_numeric($kec) && Schema::hasColumn('surveys', 'kecamatan_id')) {
                $q->where('surveys.kecamatan_id', (int)$kec);
            } elseif (Schema::hasColumn('surveys', 'kecamatan')) {
                $q->whereRaw('LOWER(TRIM(surveys.kecamatan)) = ?', [mb_strtolower($kec)]);
            }
        }

        if ($desa) {
            $desa = trim((string)$desa);
            if (is_numeric($desa) && Schema::hasColumn('surveys', 'kelurahan_id')) {
                $q->where('surveys.kelurahan_id', (int)$desa);
            } elseif (Schema::hasColumn('surveys', 'desa')) {
                $q->whereRaw('LOWER(TRIM(surveys.desa)) = ?', [mb_strtolower($desa)]);
            }
        }

        $types = $q->selectRaw('LOWER(TRIM(parcels.type)) as t')->distinct()->pluck('t')->filter()->values();

        return response()->json($types);
    }

    /**
     * ✅ API: Ambil data produksi gabungan tanaman + ternak
     */
    public function getProduksi(Request $req)
    {
        $kecParam = $req->query('kecamatan');
        $desaParam = $req->query('desa');
        $typeParam = $req->query('type');

        if (!$kecParam || !$desaParam) {
            return response()->json(['error' => 'kecamatan dan desa diperlukan'], 422);
        }

        $kecRaw = trim((string)$kecParam);
        $desaRaw = trim((string)$desaParam);
        $type = $typeParam ? trim((string)$typeParam) : null;

        // 1) cek eksistensi kombinasi kecamatan+desa di surveys (support id atau nama)
        $surveyQ = DB::table('surveys');

        // kecamatan filter untuk pengecekan eksistensi
        if (is_numeric($kecRaw) && Schema::hasColumn('surveys', 'kecamatan_id')) {
            $surveyQ->where('kecamatan_id', (int)$kecRaw);
        } else {
            if (Schema::hasColumn('surveys', 'kecamatan')) {
                $surveyQ->whereRaw('LOWER(TRIM(kecamatan)) = ?', [mb_strtolower($kecRaw)]);
            } else {
                // jika tidak ada kolom teks, dan kecRaw numeric, coba kecamatan_id
                if (is_numeric($kecRaw) && Schema::hasTable('kecamatans')) {
                    $surveyQ->where('kecamatan_id', (int)$kecRaw);
                }
            }
        }

        // desa filter untuk pengecekan eksistensi
        if (is_numeric($desaRaw) && Schema::hasColumn('surveys', 'kelurahan_id')) {
            $surveyQ->where('kelurahan_id', (int)$desaRaw);
        } else {
            if (Schema::hasColumn('surveys', 'desa')) {
                $surveyQ->whereRaw('LOWER(TRIM(desa)) = ?', [mb_strtolower($desaRaw)]);
            } else {
                if (is_numeric($desaRaw) && Schema::hasTable('kelurahans')) {
                    $surveyQ->where('kelurahan_id', (int)$desaRaw);
                }
            }
        }

        if (!$surveyQ->exists()) {
            // kombinasi kecamatan+desa tidak ada → kembalikan kosong
            return response()->json([]);
        }

        // 2) helper yang memastikan semua query mendapat filter kec + desa yang sama
        $applyFilters = function ($q) use ($kecRaw, $desaRaw) {
            // kecamatan
            if (is_numeric($kecRaw) && Schema::hasColumn('surveys', 'kecamatan_id')) {
                $q->where('surveys.kecamatan_id', (int)$kecRaw);
            } else {
                if (Schema::hasColumn('surveys', 'kecamatan')) {
                    $q->whereRaw('LOWER(TRIM(surveys.kecamatan)) = ?', [mb_strtolower($kecRaw)]);
                } elseif (is_numeric($kecRaw)) {
                    $q->where('surveys.kecamatan_id', (int)$kecRaw);
                }
            }

            // desa / kelurahan
            if (is_numeric($desaRaw) && Schema::hasColumn('surveys', 'kelurahan_id')) {
                $q->where('surveys.kelurahan_id', (int)$desaRaw);
            } else {
                if (Schema::hasColumn('surveys', 'desa')) {
                    $q->whereRaw('LOWER(TRIM(surveys.desa)) = ?', [mb_strtolower($desaRaw)]);
                } elseif (is_numeric($desaRaw)) {
                    $q->where('surveys.kelurahan_id', (int)$desaRaw);
                }
            }
        };

        // 3) ambil crops (parcel_crops)
        $cropQ = ParcelCrop::query()
            ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id');

        if ($type) {
            $cropQ->whereRaw('LOWER(TRIM(parcels.type)) = ?', [mb_strtolower($type)]);
        }
        $applyFilters($cropQ);

        $cropRows = $cropQ->selectRaw(
            'LOWER(TRIM(parcels.type)) as type, parcel_crops.nama_tanaman as nama, SUM(COALESCE(parcel_crops.luas_hektare,0)) as luas_sum, SUM(COALESCE(parcel_crops.produksi_ton,0)) as produksi_sum, MIN(parcel_crops.catatan) as catatan'
        )
            ->groupByRaw('LOWER(TRIM(parcels.type)), parcel_crops.nama_tanaman')
            ->get();

        // 4) ambil livestocks
        $lvQ = Livestock::query()
            ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
            ->join('surveys', 'parcels.survey_id', '=', 'surveys.id');

        if ($type) {
            $lvQ->whereRaw('LOWER(TRIM(parcels.type)) = ?', [mb_strtolower($type)]);
        }
        $applyFilters($lvQ);

        $lvRows = $lvQ->selectRaw(
            'LOWER(TRIM(parcels.type)) as type, livestocks.jenis_ternak as nama, SUM(COALESCE(livestocks.jumlah,0)) as jumlah_sum, MIN(livestocks.produksi) as produksi_note'
        )
            ->groupByRaw('LOWER(TRIM(parcels.type)), livestocks.jenis_ternak')
            ->get();

        // 5) gabungkan ke struktur yang frontend harapkan
        $acc = [];
        foreach ($cropRows as $r) {
            $t = $r->type ?? 'umum';
            if (!isset($acc[$t])) $acc[$t] = ['type' => $t, 'crops' => [], 'livestocks' => []];
            $acc[$t]['crops'][] = [
                'nama' => (string)$r->nama,
                'luas' => (float)$r->luas_sum,
                'produksi' => (float)$r->produksi_sum,
                'catatan' => $r->catatan,
            ];
        }
        foreach ($lvRows as $r) {
            $t = $r->type ?? 'umum';
            if (!isset($acc[$t])) $acc[$t] = ['type' => $t, 'crops' => [], 'livestocks' => []];
            $acc[$t]['livestocks'][] = [
                'nama' => (string)$r->nama,
                'jumlah' => (int)$r->jumlah_sum,
                'produksi_note' => $r->produksi_note,
            ];
        }

        if (empty($acc)) return response()->json([]);

        ksort($acc);
        $final = array_values($acc);

        return response()->json($final);
    }

    /**
     * ✅ API: Ambil semua komoditas berdasarkan jenis
     */
    public function getAllKomoditas(Request $req)
    {
        $jenis = strtolower(trim((string)$req->query('jenis')));
        $kecamatan = $req->query('kecamatan');
        $desa = $req->query('desa');

        if (!$jenis) return response()->json([]);

        if (in_array($jenis, ['livestocks', 'peternakan'])) {
            $q = Livestock::query()
                ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id');

            if ($kecamatan) $q->whereRaw('LOWER(TRIM(surveys.kecamatan)) = ?', [mb_strtolower($kecamatan)]);
            if ($desa) $q->whereRaw('LOWER(TRIM(surveys.desa)) = ?', [mb_strtolower($desa)]);

            $rows = $q->selectRaw('DISTINCT LOWER(TRIM(livestocks.jenis_ternak)) as nama')
                ->orderBy('nama')->pluck('nama');
        } else {
            $jenisType = match ($jenis) {
                'persawahan' => ['persawahan'],
                'perkebunan' => ['perkebunan'],
                'tambak' => ['tambak'],
                'komoditas_lain' => ['komoditas_lain'],
                default => ['persawahan'],
            };

            $q = ParcelCrop::query()
                ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->whereIn('parcels.type', $jenisType);

            if ($kecamatan) $q->whereRaw('LOWER(TRIM(surveys.kecamatan)) = ?', [mb_strtolower($kecamatan)]);
            if ($desa) $q->whereRaw('LOWER(TRIM(surveys.desa)) = ?', [mb_strtolower($desa)]);

            $rows = $q->selectRaw('DISTINCT LOWER(TRIM(parcel_crops.nama_tanaman)) as nama')
                ->orderBy('nama')->pluck('nama');
        }

        return response()->json($rows);
    }

    /**
     * ✅ API: Ambil desa penghasil komoditas tertentu
     */

    public function getDesaByKomoditas(Request $request)
    {
        $komoditas = trim((string)$request->query('komoditas', ''));
        $jenis     = trim((string)$request->query('jenis', ''));
        $kecParam  = $request->query('kecamatan', null);
        $desaParam = $request->query('desa', null);
        $geo       = filter_var($request->query('geo', false), FILTER_VALIDATE_BOOLEAN);

        if ($komoditas === '') {
            return response()->json([], 200);
        }

        // helper untuk apply filter kecamatan/desa ke query surveys/joins
        $applyKecDesaFilters = function ($q) use ($kecParam, $desaParam) {
            // Kecamatan filter: bisa id numeric atau nama
            if ($kecParam !== null && $kecParam !== '') {
                if (is_numeric($kecParam) && Schema::hasColumn('surveys', 'kecamatan_id')) {
                    $q->where('surveys.kecamatan_id', (int)$kecParam);
                } else {
                    // try to match kecamatans table if exists
                    if (Schema::hasTable('kecamatans')) {
                        $kecName = mb_strtolower(trim((string)$kecParam));
                        $q->whereRaw('LOWER(TRIM(kecamatans.nama_kecamatan)) = ?', [$kecName]);
                    } else {
                        $q->whereRaw('LOWER(TRIM(surveys.kecamatan)) = ?', [mb_strtolower(trim((string)$kecParam))]);
                    }
                }
            }

            // Desa filter: id numeric -> kelurahan_id; else match name (kelurahans or surveys.desa)
            if ($desaParam !== null && $desaParam !== '') {
                if (is_numeric($desaParam) && Schema::hasColumn('surveys', 'kelurahan_id')) {
                    $q->where('surveys.kelurahan_id', (int)$desaParam);
                } else {
                    if (Schema::hasTable('kelurahans')) {
                        $desaName = mb_strtolower(trim((string)$desaParam));
                        $q->whereRaw('LOWER(TRIM(kelurahans.nama_kelurahan)) = ?', [$desaName]);
                    } else {
                        $q->whereRaw('LOWER(TRIM(surveys.desa)) = ?', [mb_strtolower(trim((string)$desaParam))]);
                    }
                }
            }
        };

        // We'll build specific queries for each jenis and join to kelurahans + kecamatans + wilayah_locations
        $exprKec = 'kecamatans.nama_kecamatan';
        $exprDes = 'kelurahans.nama_kelurahan';

        $q = null;

        // PETERNKAN (livestocks)
        if ($jenis === 'peternakan' || Str::contains($jenis, 'ternak')) {
            $q = DB::table('livestocks')
                ->join('parcels', 'livestocks.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->leftJoin('kecamatans', 'surveys.kecamatan_id', '=', 'kecamatans.id')
                ->leftJoin('kelurahans', 'surveys.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('wilayah_locations as l', function ($join) {
                    $join->on('kelurahans.id', '=', 'l.kelurahan_id')
                        ->orOn(DB::raw('LOWER(TRIM(kelurahans.nama_kelurahan))'), '=', DB::raw('LOWER(TRIM(l.nama_kelurahan))'));
                })
                ->whereRaw('LOWER(TRIM(livestocks.jenis_ternak)) = ?', [mb_strtolower($komoditas)])
                ->selectRaw("
                {$exprKec} AS nama_kecamatan,
                {$exprDes} AS nama_kelurahan,
                COALESCE(SUM(livestocks.jumlah),0) AS jumlah_sum,
                COALESCE(MIN(livestocks.produksi), '-') AS produksi_note,
                l.latitude, l.longitude
            ")
                ->groupByRaw("{$exprKec}, {$exprDes}, l.latitude, l.longitude");
            // apply filters
            $applyKecDesaFilters($q);
        }

        // PERTANIAN / PERSAWAHAN
        if (($jenis === 'persawahan' || strtolower($jenis) === 'pertanian')) {
            $q = DB::table('parcel_crops')
                ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->leftJoin('kecamatans', 'surveys.kecamatan_id', '=', 'kecamatans.id')
                ->leftJoin('kelurahans', 'surveys.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('wilayah_locations as l', function ($join) {
                    $join->on('kelurahans.id', '=', 'l.kelurahan_id')
                        ->orOn(DB::raw('LOWER(TRIM(kelurahans.nama_kelurahan))'), '=', DB::raw('LOWER(TRIM(l.nama_kelurahan))'));
                })
                ->whereRaw('LOWER(TRIM(parcel_crops.nama_tanaman)) = ?', [mb_strtolower($komoditas)])
                ->whereRaw('LOWER(TRIM(parcels.type)) = ?', ['persawahan'])
                ->selectRaw("
                {$exprKec} AS nama_kecamatan,
                {$exprDes} AS nama_kelurahan,
                COALESCE(SUM(parcel_crops.luas_hektare),0) AS luas,
                COALESCE(SUM(parcel_crops.produksi_ton),0) AS produksi,
                l.latitude, l.longitude
            ")
                ->groupByRaw("{$exprKec}, {$exprDes}, l.latitude, l.longitude");
            $applyKecDesaFilters($q);
        }

        // PERKEBUNAN
        if ($jenis === 'perkebunan') {
            $q = DB::table('parcel_crops')
                ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->leftJoin('kecamatans', 'surveys.kecamatan_id', '=', 'kecamatans.id')
                ->leftJoin('kelurahans', 'surveys.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('wilayah_locations as l', function ($join) {
                    $join->on('kelurahans.id', '=', 'l.kelurahan_id')
                        ->orOn(DB::raw('LOWER(TRIM(kelurahans.nama_kelurahan))'), '=', DB::raw('LOWER(TRIM(l.nama_kelurahan))'));
                })
                ->whereRaw('LOWER(TRIM(parcel_crops.nama_tanaman)) = ?', [mb_strtolower($komoditas)])
                ->whereRaw('LOWER(TRIM(parcels.type)) = ?', ['perkebunan'])
                ->selectRaw("
                {$exprKec} AS nama_kecamatan,
                {$exprDes} AS nama_kelurahan,
                COALESCE(SUM(parcel_crops.luas_hektare),0) AS luas,
                COALESCE(SUM(parcel_crops.produksi_ton),0) AS produksi,
                l.latitude, l.longitude
            ")
                ->groupByRaw("{$exprKec}, {$exprDes}, l.latitude, l.longitude");
            $applyKecDesaFilters($q);
        }

        // TAMBAK / PERIKANAN
        if (in_array($jenis, ['tambak', 'perikanan'])) {
            $q = DB::table('parcel_crops')
                ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->leftJoin('kecamatans', 'surveys.kecamatan_id', '=', 'kecamatans.id')
                ->leftJoin('kelurahans', 'surveys.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('wilayah_locations as l', function ($join) {
                    $join->on('kelurahans.id', '=', 'l.kelurahan_id')
                        ->orOn(DB::raw('LOWER(TRIM(kelurahans.nama_kelurahan))'), '=', DB::raw('LOWER(TRIM(l.nama_kelurahan))'));
                })
                ->whereRaw('LOWER(TRIM(parcel_crops.nama_tanaman)) = ?', [mb_strtolower($komoditas)])
                ->whereRaw('LOWER(TRIM(parcels.type)) = ?', ['tambak'])
                ->selectRaw("
                {$exprKec} AS nama_kecamatan,
                {$exprDes} AS nama_kelurahan,
                COALESCE(SUM(parcel_crops.luas_hektare),0) AS luas,
                COALESCE(SUM(parcel_crops.produksi_ton),0) AS produksi,
                l.latitude, l.longitude
            ")
                ->groupByRaw("{$exprKec}, {$exprDes}, l.latitude, l.longitude");
            $applyKecDesaFilters($q);
        }

        // KOMODITAS LAIN (default)
        if (!$q) {
            // fallback: coba cari di parcel_crops tanpa filter type
            $q = DB::table('parcel_crops')
                ->join('parcels', 'parcel_crops.parcel_id', '=', 'parcels.id')
                ->join('surveys', 'parcels.survey_id', '=', 'surveys.id')
                ->leftJoin('kecamatans', 'surveys.kecamatan_id', '=', 'kecamatans.id')
                ->leftJoin('kelurahans', 'surveys.kelurahan_id', '=', 'kelurahans.id')
                ->leftJoin('wilayah_locations as l', function ($join) {
                    $join->on('kelurahans.id', '=', 'l.kelurahan_id')
                        ->orOn(DB::raw('LOWER(TRIM(kelurahans.nama_kelurahan))'), '=', DB::raw('LOWER(TRIM(l.nama_kelurahan))'));
                })
                ->whereRaw('LOWER(TRIM(parcel_crops.nama_tanaman)) = ?', [mb_strtolower($komoditas)])
                ->selectRaw("
                {$exprKec} AS nama_kecamatan,
                {$exprDes} AS nama_kelurahan,
                COALESCE(SUM(parcel_crops.luas_hektare),0) AS luas,
                COALESCE(SUM(parcel_crops.produksi_ton),0) AS produksi,
                l.latitude, l.longitude
            ")
                ->groupByRaw("{$exprKec}, {$exprDes}, l.latitude, l.longitude");
            $applyKecDesaFilters($q);
        }

        // Ambil data & filter lagi supaya hanya yang memiliki nilai produksi/luas/jumlah > 0 dan ada coords
        $rows = $q->get()->filter(function ($r) {
            // produksi/luas/jumlah bisa memakai field yang berbeda tergantung jenis
            $hasNonZero = false;
            foreach (['produksi', 'produksi_sum', 'luas', 'jumlah_sum', 'jumlah'] as $f) {
                if (isset($r->{$f}) && $r->{$f} !== null && floatval($r->{$f}) > 0) {
                    $hasNonZero = true;
                    break;
                }
            }
            // also require coordinates (if you want to show markers only when coords exist)
            $hasCoords = isset($r->latitude) && isset($r->longitude) && $r->latitude !== null && $r->longitude !== null;
            return $hasNonZero && $hasCoords;
        })->values();

        // Jika diminta geojson
        if ($geo) {
            $features = $rows->map(function ($r) {
                return [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [(float)$r->longitude, (float)$r->latitude]],
                    'properties' => [
                        'nama_kecamatan' => $r->nama_kecamatan ?? null,
                        'nama_kelurahan' => $r->nama_kelurahan ?? null,
                        'produksi' => $r->produksi ?? ($r->produksi_sum ?? null),
                        'luas' => $r->luas ?? null,
                        'jumlah' => $r->jumlah_sum ?? $r->jumlah ?? null,
                    ],
                ];
            })->values();

            return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
        }

        return response()->json($rows);
    }
}
