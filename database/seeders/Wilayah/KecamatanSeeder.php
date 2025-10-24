<?php

namespace Database\Seeders\Wilayah;

use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_kabupaten' => 3318,
                'id' => 331801,
                'nama_kecamatan' => 'SUKOLILO',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331802,
                'nama_kecamatan' => 'KAYEN',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331803,
                'nama_kecamatan' => 'TAMBAKROMO',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331804,
                'nama_kecamatan' => 'WINONG',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331805,
                'nama_kecamatan' => 'PUCAKWANGI',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331806,
                'nama_kecamatan' => 'JAKEN',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331807,
                'nama_kecamatan' => 'BATANGAN',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331808,
                'nama_kecamatan' => 'JUWANA',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331809,
                'nama_kecamatan' => 'JAKENAN',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331810,
                'nama_kecamatan' => 'PATI',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331811,
                'nama_kecamatan' => 'GABUS',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331812,
                'nama_kecamatan' => 'MARGOREJO',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331813,
                'nama_kecamatan' => 'GEMBONG',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331814,
                'nama_kecamatan' => 'TLOGOWUNGU',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331815,
                'nama_kecamatan' => 'WEDARIJAKSA',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331816,
                'nama_kecamatan' => 'MARGOYOSO',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331817,
                'nama_kecamatan' => 'GUNUNGWUNGKAL',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331818,
                'nama_kecamatan' => 'CLUWAK',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331819,
                'nama_kecamatan' => 'TAYU',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331820,
                'nama_kecamatan' => 'DUKUHSETI',
                'status' => 'Aktif',
            ],
            [
                'id_kabupaten' => 3318,
                'id' => 331821,
                'nama_kecamatan' => 'TRANGKIL',
                'status' => 'Aktif',
            ]
        ];

        foreach ($data as $key => $value) {
            Kecamatan::create($value);
        }
    }
}
