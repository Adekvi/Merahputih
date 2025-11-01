<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ContohSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        // Pastikan tabel-tabel wilayah sudah ter-seed
        if (! Schema::hasTable('kecamatans') || ! Schema::hasTable('kelurahans') || ! Schema::hasTable('surveys')) {
            $this->command->error('Pastikan seeders wilayah (Kabupaten/Kecamatan/Kelurahan) dan tabel surveys ada sebelum menjalankan seeder ini.');
            return;
        }

        // 1) pilih kecamatan (pakai salah satu id dari KecamatanSeeder)
        // Contoh: SUKOLILO => 331801
        $kecamatanBpsId = 331801;

        $kecamatan = DB::table('kecamatans')->where('id', $kecamatanBpsId)->first();
        if (! $kecamatan) {
            $this->command->error("Kecamatan dengan id {$kecamatanBpsId} tidak ditemukan. Jalankan KecamatanSeeder dahulu.");
            return;
        }

        // 2) cari kelurahan contoh di kecamatan tersebut
        $kelurahanName = 'Batursari'; // pilih salah satu nama dari KelurahanSeeder mapping untuk SUKOLILO
        $kelurahanQuery = DB::table('kelurahans')
            ->whereRaw('LOWER(TRIM(nama_kelurahan)) = ?', [strtolower(trim($kelurahanName))]);

        // filter by kecamatan id column (id_kecamatan or kecamatan_id) if present
        if (Schema::hasColumn('kelurahans', 'id_kecamatan')) {
            $kelurahanQuery->where('id_kecamatan', $kecamatanBpsId);
        } elseif (Schema::hasColumn('kelurahans', 'kecamatan_id')) {
            $kelurahanQuery->where('kecamatan_id', $kecamatanBpsId);
        }

        $kelurahan = $kelurahanQuery->first();

        // jika tidak ditemukan, buat kelurahan baru dan isi FK kecamatan jika kolom tersedia
        if (! $kelurahan) {
            $kelData = [
                'nama_kelurahan' => $kelurahanName,
                'status' => 'Aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (Schema::hasColumn('kelurahans', 'id_kecamatan')) {
                $kelData['id_kecamatan'] = $kecamatanBpsId;
            } elseif (Schema::hasColumn('kelurahans', 'kecamatan_id')) {
                $kelData['kecamatan_id'] = $kecamatanBpsId;
            }
            $kelurahanId = DB::table('kelurahans')->insertGetId($kelData);
            $kelurahan = DB::table('kelurahans')->where('id', $kelurahanId)->first();
        }

        // 3) tentukan user_id (cari admin jika ada)
        $user = DB::table('users')->where('username', 'admin')->orWhere('role', 'admin')->first();
        $userId = $user ? $user->id : (DB::table('users')->value('id') ?? 1);

        // 4) Buat survey (insert sementara dengan no_id TMP)
        $tempNo = 'TMP-' . (string) Str::uuid();

        $surveyId = DB::table('surveys')->insertGetId([
            'user_id' => $userId,
            'no_id' => $tempNo,
            // kolom surveys kemungkinan bernama kecamatan_id & kelurahan_id
            'kecamatan_id' => isset($kecamatan->id) ? $kecamatan->id : null,
            'kelurahan_id' => isset($kelurahan->id) ? $kelurahan->id : null,
            'jumlah_penduduk' => 1350,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5) generate no_id final dan update
        $kecName = $kecamatan->nama_kecamatan ?? 'KEC';
        $kelName = $kelurahan->nama_kelurahan ?? 'DES';

        $kecCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $kecName));
        $kecCode = substr($kecCode, 0, 3) ?: 'KEC';

        $kelCode = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $kelName));
        $kelCode = substr($kelCode, 0, 3) ?: 'DES';

        $date = Carbon::now()->format('Ymd');
        $seq = sprintf('%04d', $surveyId);
        $generatedNoId = "KEC{$kecCode}-DES{$kelCode}-{$date}-{$seq}";

        DB::table('surveys')->where('id', $surveyId)->update([
            'no_id' => $generatedNoId,
            'updated_at' => $now,
        ]);

        // 6) Tambah beberapa parcels & details contoh
        // Tambak
        $tambakId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'tambak',
            'area_hectare' => 4.2,
            'keterangan' => 'Lahan tambak contoh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('parcel_crops')->insert([
            [
                'parcel_id' => $tambakId,
                'nama_tanaman' => 'ikan bandeng',
                'luas_hektare' => 3.000,
                'produksi_ton' => 9.000,
                'satuan' => 'ton',
                'catatan' => 'Panen per tahun',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parcel_id' => $tambakId,
                'nama_tanaman' => 'udang',
                'luas_hektare' => 1.200,
                'produksi_ton' => 3.500,
                'satuan' => 'ton',
                'catatan' => 'Panen setiap 6 bulan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Peternakan
        $peternakanId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'peternakan',
            'area_hectare' => null,
            'keterangan' => 'Peternakan unggas contoh',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('livestocks')->insert([
            'parcel_id' => $peternakanId,
            'jenis_ternak' => 'ayam pedaging',
            'jumlah' => 500,
            'produksi' => 'sekitar 1.2 ton daging per periode panen',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Komoditas lain
        $komoditasId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'komoditas_lain',
            'area_hectare' => 0.5,
            'keterangan' => 'Olahan kerupuk ikan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('parcel_crops')->insert([
            [
                'parcel_id' => $komoditasId,
                'nama_tanaman' => 'kerupuk ikan',
                'luas_hektare' => 0.500,
                'produksi_ton' => 1.200,
                'satuan' => 'ton',
                'catatan' => 'Hasil olahan ikan lokal',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->command->info("✅ Contoh survey dibuat. survey.no_id = {$generatedNoId} (survey id: {$surveyId})");
    }
}
