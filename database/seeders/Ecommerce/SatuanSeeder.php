<?php

namespace Database\Seeders\Ecommerce;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('satuans')->insert([
            // Berat
            ['nama_satuan' => 'kg', 'kategori' => 'berat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'kwintal', 'kategori' => 'berat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'ton', 'kategori' => 'berat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'gram', 'kategori' => 'berat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'ons', 'kategori' => 'berat', 'created_at' => now(), 'updated_at' => now()],
            
            // Volume
            ['nama_satuan' => 'liter', 'kategori' => 'volume', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'ml', 'kategori' => 'volume', 'created_at' => now(), 'updated_at' => now()],

            // Kemasan
            ['nama_satuan' => 'karung', 'kategori' => 'kemasan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'sak', 'kategori' => 'kemasan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'dus', 'kategori' => 'kemasan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
