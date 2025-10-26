<?php

namespace Database\Seeders\Ecommerce;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            [
                'id_kecamatan' => 331801,
                'id_kelurahan' => 3318012004,
                'nama_supplier' => 'UD. Rojo Lele',
                'nama_barang' => 'Beras',
                'kategori' => 'Pertanian',
                'deskripsi' => 'Beras kualitas premium',
                'tgl_sup' => '2025-10-25',
                'jumlah' => 16,
                'satuan_jumlah_id' => 3,
                'harga' => 15000.00,
                'satuan_harga_id' => 1,
                'alamat' => 'Jl. Merpati No. 45, Desa Kedumulyo',
                'no_hp' => '087987234543',
                'gambar' => '/aset/img/produk/beras.jpg',
                'status' => 'suply',
            ],[
                'id_kecamatan' => 331801,
                'id_kelurahan' => 3318012005,
                'nama_supplier' => 'UD. Konco Tani',
                'nama_barang' => 'Jagung',
                'kategori' => 'Perkebunan',
                'deskripsi' => 'Jagung tongkol kualitas unggul',
                'tgl_sup' => '2025-10-25',
                'jumlah' => 20,
                'satuan_jumlah_id' => 3,
                'harga' => 4500.00,
                'satuan_harga_id' => 1,
                'alamat' => 'Jl. Pangan No. 20, Desa Kedumulyo',
                'no_hp' => '087987234544',
                'gambar' => '/aset/img/produk/jagung.jpg',
                'status' => 'suply',
            ],[
                'id_kecamatan' => 331801,
                'id_kelurahan' => 3318012006,
                'nama_supplier' => 'PG. Rendeng',
                'nama_barang' => 'Gula Pasir',
                'kategori' => 'Perkebunan',
                'deskripsi' => 'Gula pasir kualitas terbaik',
                'tgl_sup' => '2025-10-25',
                'jumlah' => 15,
                'satuan_jumlah_id' => 3,
                'harga' => 13000.00,
                'satuan_harga_id' => 1,
                'alamat' => 'Jl. Pangan No. 20, Desa Kedumulyo',
                'no_hp' => '087987234544',
                'gambar' => '/aset/img/produk/gula-pasir.jpg',
                'status' => 'suply',
            ]
        ];

        DB::table('supplies')->insert($data);
    }
}
