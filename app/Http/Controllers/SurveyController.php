<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Parcel;
use App\Models\ParcelCrop;
use App\Models\Livestock;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data dengan relasi untuk ditampilkan di Blade
        $query = Survey::with(['parcels.crops', 'parcels.livestock']);

        // Cek role dari kolom 'role' di tabel users
        if ($user && $user->role !== 'admin') {
            // Kalau bukan admin, tampilkan hanya data milik user sendiri
            $query->where('user_id', $user->id);
        }

        // Urutkan dari yang terbaru
        $surveys = $query->orderBy('created_at', 'desc')->get();

        return view('surveys.index', compact('surveys'));
    }
    public function create()
    {
        return view('surveys.create');
    }

    public function createvoice()
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('surveys.create_voice', compact('kecamatans'));
    }


    public function store(Request $request)
    {
        $input = $request->all();

        if ((empty($input['parcels']) || !is_array($input['parcels'])) && !empty($input['parcels_json'])) {
            $decoded = json_decode($input['parcels_json'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $input['parcels'] = $decoded;
            } else {
                return redirect()->back()->withErrors(['parcels_json' => 'Format JSON potensi lahan tidak valid.'])->withInput();
            }
        }

        // VALIDATION
        $rules = [
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'jumlah_penduduk' => 'nullable|integer|min:0',

            'parcels' => 'required|array|min:1',
            'parcels.*.type' => 'required|in:persawahan,perkebunan,tambak,peternakan,komoditas_lain',
            'parcels.*.crops' => 'nullable|array',
            'parcels.*.crops.*.nama_tanaman' => 'required_with:parcels.*.crops|string|max:200',
            'parcels.*.crops.*.luas_hektare' => 'nullable|numeric|min:0',
            'parcels.*.crops.*.produksi_ton' => 'nullable|numeric|min:0',
            'parcels.*.crops.*.satuan' => 'nullable|string|max:20',
            'parcels.*.livestocks' => 'nullable|array',
            'parcels.*.livestocks.*.jenis_ternak' => 'required_with:parcels.*.livestocks|string|max:150',
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Jika front-end mengirim nama kec/desa (suatu fallback saat voice) -> coba resolve ke id
            if (empty($input['kecamatan_id']) && !empty($input['kecamatan'])) {
                $kecModel = Kecamatan::firstOrCreate(['nama' => $input['kecamatan']]);
                $input['kecamatan_id'] = $kecModel->id;
            }
            if (empty($input['kelurahan_id']) && !empty($input['desa'])) {
                // Jika kelurahan perlu dihubungkan ke kecamatan, sertakan kecamatan_id yang sudah ada
                $kelAttrs = ['nama' => $input['desa']];
                if (!empty($input['kecamatan_id'])) $kelAttrs['kecamatan_id'] = $input['kecamatan_id'];
                $kelModel = Kelurahan::firstOrCreate($kelAttrs);
                $input['kelurahan_id'] = $kelModel->id;
            }

            $tempNoId = 'TMP-' . (string) Str::uuid();

            $survey = Survey::create([
                'no_id' => $tempNoId,
                'kecamatan_id' => $input['kecamatan_id'] ?? null,
                'kelurahan_id' => $input['kelurahan_id'] ?? null,
                'jumlah_penduduk' => $input['jumlah_penduduk'] ?? null,
                'user_id' => Auth::id(),
            ]);

            // generate final no_id berdasarkan id dan kode kec/kel (ambil nama jika tersedia)
            $kecName = $survey->kecamatan?->nama ?? ($input['kecamatan'] ?? 'KEC');
            $kelName = $survey->kelurahan?->nama ?? ($input['desa'] ?? 'DES');

            $kecCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $kecName));
            $kecCode = substr($kecCode, 0, 3) ?: 'KEC';

            $kelCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $kelName));
            $kelCode = substr($kelCode, 0, 3) ?: 'DES';

            $date = Carbon::now()->format('Ymd');
            $seq = sprintf('%04d', $survey->id);
            $generatedNoId = "KEC{$kecCode}-DES{$kelCode}-{$date}-{$seq}";

            $survey->no_id = $generatedNoId;
            $survey->save();

            // Save parcels
            $parcels = $input['parcels'] ?? [];
            foreach ($parcels as $parcelData) {
                if (empty($parcelData['type'])) continue;
                $parcel = Parcel::create([
                    'survey_id' => $survey->id,
                    'type' => $parcelData['type'],
                ]);

                if (!empty($parcelData['crops']) && is_array($parcelData['crops'])) {
                    foreach ($parcelData['crops'] as $crop) {
                        if (empty($crop['nama_tanaman'])) continue;
                        ParcelCrop::create([
                            'parcel_id' => $parcel->id,
                            'nama_tanaman' => $crop['nama_tanaman'],
                            'luas_hektare' => $crop['luas_hektare'] ?? null,
                            'produksi_ton' => $crop['produksi_ton'] ?? null,
                            'satuan' => $crop['satuan'] ?? null,
                            'catatan' => $crop['catatan'] ?? null,
                        ]);
                    }
                }

                if (!empty($parcelData['livestocks']) && is_array($parcelData['livestocks'])) {
                    foreach ($parcelData['livestocks'] as $l) {
                        if (empty($l['jenis_ternak'])) continue;
                        Livestock::create([
                            'parcel_id' => $parcel->id,
                            'jenis_ternak' => $l['jenis_ternak'],
                            'jumlah' => $l['jumlah'] ?? null,
                            'produksi' => $l['produksi'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            $successMsg = 'Data sensus berhasil disimpan. ID: ' . $survey->no_id;

            if (!empty($input['parcels_json']) || $request->has('_from_voice')) {
                return redirect()->route('surveys.voicecreate')->with('success', $successMsg)->with('latest_no_id', $survey->no_id);
            }

            return redirect()->route('surveys.index')->with('success', $successMsg)->with('latest_no_id', $survey->no_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }


    public function dashboard()
    {
        $user = Auth::user();

        // 🔒 Batasi data sesuai role
        if ($user->role === 'admin') {
            // Admin bisa lihat semua data
            $surveys = Survey::with(['parcels.crops', 'parcels.livestock'])->get();
        } else {
            // User biasa hanya bisa lihat data miliknya
            $surveys = Survey::with(['parcels.crops', 'parcels.livestock'])
                ->where('user_id', $user->id)
                ->get();
        }

        // Olah data agregat
        $totalProduksiPertanian = 0;
        $totalPopulasiTernak = 0;
        $totalProduksiPerkebunan = 0;
        $totalHortikultura = 0;
        $jumlahPetani = $surveys->count();

        $persawahan = [];
        $perkebunan = [];
        $peternakan = [];
        $hortikultura = [];

        foreach ($surveys as $survey) {
            foreach ($survey->parcels as $parcel) {
                if ($parcel->type === 'persawahan') {
                    foreach ($parcel->crops as $crop) {
                        $persawahan[$crop->nama_tanaman] = ($persawahan[$crop->nama_tanaman] ?? 0) + ($crop->produksi_ton ?? 0);
                        $totalProduksiPertanian += $crop->produksi_ton ?? 0;
                    }
                }

                if ($parcel->type === 'perkebunan') {
                    foreach ($parcel->crops as $crop) {
                        $perkebunan[$crop->nama_tanaman] = ($perkebunan[$crop->nama_tanaman] ?? 0) + ($crop->produksi_ton ?? 0);
                        $totalProduksiPerkebunan += $crop->produksi_ton ?? 0;
                    }
                }

                if ($parcel->type === 'peternakan') {
                    foreach ($parcel->livestock as $livestock) {
                        $peternakan[$livestock->jenis_ternak] = ($peternakan[$livestock->jenis_ternak] ?? 0) + ($livestock->jumlah ?? 0);
                        $totalPopulasiTernak += $livestock->jumlah ?? 0;
                    }
                }

                if ($parcel->type === 'komoditas_lain') {
                    foreach ($parcel->crops as $crop) {
                        $hortikultura[$crop->nama_tanaman] = ($hortikultura[$crop->nama_tanaman] ?? 0) + ($crop->produksi_ton ?? 0);
                        $totalHortikultura += $crop->produksi_ton ?? 0;
                    }
                }
            }
        }

        // Format ulang array agar cocok dengan dashboard JS
        $DATA = [
            'periode' => now()->year,
            'total_produksi_pertanian_ton' => $totalProduksiPertanian,
            'total_populasi_ternak' => $totalPopulasiTernak,
            'total_produksi_perkebunan_ton' => $totalProduksiPerkebunan,
            'total_hortikultura_ton' => $totalHortikultura,
            'jumlah_petani' => $jumlahPetani,
            'persawahan' => collect($persawahan)->map(fn($v, $k) => ['jenis' => $k, 'ton' => $v])->values(),
            'perkebunan' => collect($perkebunan)->map(fn($v, $k) => ['jenis' => $k, 'ton' => $v])->values(),
            'peternakan' => collect($peternakan)->map(fn($v, $k) => ['jenis' => $k, 'jumlah' => $v])->values(),
            'hortikultura' => collect($hortikultura)->map(fn($v, $k) => ['jenis' => $k, 'ton' => $v])->values(),
        ];

        return view('dashboard', compact('DATA'));
    }
    public function kelurahansByKecamatan($kecamatanId)
    {
        // gunakan kolom id_kecamatan dan kembalikan field id + nama_kelurahan sebagai 'nama'
        $kel = Kelurahan::where('id_kecamatan', $kecamatanId)
            ->orderBy('nama_kelurahan')
            ->get(['id', 'nama_kelurahan']);

        // normalisasi response agar frontend dapat baca field "nama"
        $kel = $kel->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama_kelurahan,
            ];
        });

        return response()->json($kel);
    }
}
