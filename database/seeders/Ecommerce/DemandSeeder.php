<?php

namespace Database\Seeders\Ecommerce;

use App\Models\ECommerce\Demand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_kecamatan' => 331801,
                'id_kelurahan' => 3318012014,
                'nama_barang' => 'Beras',
                'kategori' => 'Pertanian',
                'deskripsi' => 'Beras kualitas premium',
                'tgl_dem' => '2025-10-25',
                'jumlah' => 10,
                'satuan_jumlah_id' => 3,
                'harga' => 15000.00,
                'satuan_harga_id' => 1,
                'alamat' => 'Jl. Merpati No. 45, Desa Kedumulyo',
                'no_hp' => '087987234543',
                'gambar' => '/aset/img/produk/beras.jpg',
                'status' => 'request',
            ],[
                'id_kecamatan' => 331801,
                'id_kelurahan' => 3318012013,
                'nama_barang' => 'Jagung',
                'kategori' => 'Perkebunan',
                'deskripsi' => 'Jagung tongkol kualitas unggul',
                'tgl_dem' => '2025-10-25',
                'jumlah' => 20,
                'satuan_jumlah_id' => 3,
                'harga' => 4500.00,
                'satuan_harga_id' => 1,
                'alamat' => 'Jl. Pangan No. 20, Desa Kedumulyo',
                'no_hp' => '087987234544',
                'gambar' => '/aset/img/produk/jagung.jpg',
                'status' => 'request',
            ]
        ];
        
        foreach($data as $key => $value){
            Demand::create($value);
        }
    }
}
