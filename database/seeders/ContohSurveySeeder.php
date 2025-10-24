<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContohSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        /*
        =====================================
        1️⃣ Tambah data survey (desa/kecamatan)
        =====================================
        */
        $surveyId = DB::table('surveys')->insertGetId([
            'no_id' => 'KEC001-DESA001-20251021',
            'kecamatan' => 'Kecamatan Contoh',
            'desa' => 'Desa A',
            'jumlah_penduduk' => 1350,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        =====================================
        2️⃣ Tambah data lahan tambak
        =====================================
        */
        $tambakId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'tambak',
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

        /*
        =====================================
        3️⃣ Tambah data peternakan
        =====================================
        */
        $peternakanId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'peternakan',
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

        /*
        =====================================
        4️⃣ Tambah data komoditas lain (garam & kerupuk)
        =====================================
        */
        $garamId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'type' => 'komoditas_lain',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('parcel_crops')->insert([
            'parcel_id' => $garamId,
            'nama_tanaman' => 'garam',
            'luas_hektare' => 1.000,
            'produksi_ton' => 4.500,
            'satuan' => 'ton',
            'catatan' => 'Produksi tahunan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $kerupukId = DB::table('parcels')->insertGetId([
            'survey_id' => $surveyId,
            'keterangan' => 'Usaha kerupuk ikan industri rumah tangga',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('parcel_crops')->insert([
            'parcel_id' => $kerupukId,
            'nama_tanaman' => 'kerupuk ikan',
            'luas_hektare' => 0.500,
            'produksi_ton' => 1.200,
            'satuan' => 'ton',
            'catatan' => 'Hasil olahan ikan lokal',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        =====================================
        ✅ SELESAI — data contoh berhasil dibuat
        =====================================
        */
    }
}
