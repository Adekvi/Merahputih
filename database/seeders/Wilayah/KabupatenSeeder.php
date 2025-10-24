<?php

namespace Database\Seeders\Wilayah;

use App\Models\Wilayah\Kabupaten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 3318,
                'nama_kabupaten' => 'KABUPATEN PATI',
            ]
        ];

        foreach ($data as $key => $value) {
            Kabupaten::create($value);
        }
    }
}
