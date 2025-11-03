<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContohSurveySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        if (!Schema::hasTable('kecamatans') || !Schema::hasTable('kelurahans')) {
            $this->command->error('Seeder gagal: tabel wilayah belum tersedia.');
            return;
        }

        $user = DB::table('users')->where('username', 'admin')->orWhere('role', 'admin')->first();
        $userId = $user ? $user->id : (DB::table('users')->value('id') ?? 1);

        // 🔹 Daftar contoh kecamatan dan desanya (pastikan ada di DB)
        $dataWilayah = [
            ['kecamatan' => 'SUKOLILO', 'desa' => 'Batursari'],
            ['kecamatan' => 'SUKOLILO', 'desa' => 'Gembong'],
            ['kecamatan' => 'JUWANA', 'desa' => 'Bajomulyo'],
            ['kecamatan' => 'JUWANA', 'desa' => 'Bumirejo'],
            ['kecamatan' => 'BATANGAN', 'desa' => 'Bulumulyo'],
            ['kecamatan' => 'KAYEN', 'desa' => 'Pasuruhan'],
            ['kecamatan' => 'MARGOYOSO', 'desa' => 'Tunjungrejo'],
        ];

        $i = 1;
        foreach ($dataWilayah as $wil) {
            $kecName = $wil['kecamatan'];
            $desaName = $wil['desa'];

            $kecamatan = DB::table('kecamatans')
                ->whereRaw('LOWER(TRIM(nama_kecamatan)) = ?', [strtolower($kecName)])
                ->first();

            if (!$kecamatan) {
                $this->command->warn("⚠️ Kecamatan {$kecName} tidak ditemukan, dilewati");
                continue;
            }

            $kel = DB::table('kelurahans')
                ->whereRaw('LOWER(TRIM(nama_kelurahan)) = ?', [strtolower($desaName)])
                ->first();

            if (!$kel) {
                $kelData = [
                    'nama_kelurahan' => $desaName,
                    'status' => 'Aktif',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if (Schema::hasColumn('kelurahans', 'id_kecamatan')) {
                    $kelData['id_kecamatan'] = $kecamatan->id;
                } elseif (Schema::hasColumn('kelurahans', 'kecamatan_id')) {
                    $kelData['kecamatan_id'] = $kecamatan->id;
                }
                $kelId = DB::table('kelurahans')->insertGetId($kelData);
                $kel = DB::table('kelurahans')->where('id', $kelId)->first();
            }

            // 🔹 Insert ke surveys
            $surveyId = DB::table('surveys')->insertGetId([
                'user_id' => $userId,
                'no_id' => 'TMP-' . Str::uuid(),
                'kecamatan_id' => $kecamatan->id ?? null,
                'kelurahan_id' => $kel->id ?? null,
                'jumlah_penduduk' => rand(1000, 3500),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $kecCode = substr(strtoupper(preg_replace('/[^A-Z]/', '', $kecName)), 0, 3);
            $kelCode = substr(strtoupper(preg_replace('/[^A-Z]/', '', $desaName)), 0, 3);
            $noid = "KEC{$kecCode}-DES{$kelCode}-" . $now->format('Ymd') . "-" . sprintf('%04d', $surveyId);

            DB::table('surveys')->where('id', $surveyId)->update(['no_id' => $noid]);

            // --- Potensi Lahan ---
            // Tambak
            $tambakId = DB::table('parcels')->insertGetId([
                'survey_id' => $surveyId,
                'type' => 'tambak',
                'area_hectare' => rand(2, 6) + (rand(0, 9) / 10),
                'keterangan' => 'Lahan tambak potensial',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('parcel_crops')->insert([
                [
                    'parcel_id' => $tambakId,
                    'nama_tanaman' => 'ikan bandeng',
                    'luas_hektare' => rand(2, 4),
                    'produksi_ton' => rand(8, 15),
                    'satuan' => 'ton',
                    'catatan' => 'Panen per tahun',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'parcel_id' => $tambakId,
                    'nama_tanaman' => 'udang',
                    'luas_hektare' => rand(1, 3),
                    'produksi_ton' => rand(2, 5),
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
                'keterangan' => 'Peternakan unggas lokal',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('livestocks')->insert([
                'parcel_id' => $peternakanId,
                'jenis_ternak' => 'ayam pedaging',
                'jumlah' => rand(400, 800),
                'produksi' => 'sekitar 1.2 ton daging per periode panen',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Komoditas lain
            $komoId = DB::table('parcels')->insertGetId([
                'survey_id' => $surveyId,
                'type' => 'komoditas_lain',
                'area_hectare' => 0.5,
                'keterangan' => 'Produksi olahan lokal',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('parcel_crops')->insert([
                'parcel_id' => $komoId,
                'nama_tanaman' => 'kerupuk ikan',
                'luas_hektare' => 0.5,
                'produksi_ton' => rand(1, 2),
                'satuan' => 'ton',
                'catatan' => 'Hasil olahan ikan lokal',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->command->info("✅ [{$i}] Survey {$desaName}, {$kecName} selesai. (survey.id={$surveyId})");
            $i++;
        }
    }
}
