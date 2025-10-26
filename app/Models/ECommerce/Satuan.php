<?php

namespace App\Models\ECommerce;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Supply;
use App\Models\Ecommerce\Demand;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $guarded = [];

    public function produkJumlah()
    {
        return $this->hasMany(Product::class, 'satuan_jumlah_id');
    }

    public function produkHarga()
    {
        return $this->hasMany(Product::class, 'satuan_harga_id');
    }

    public function suplyJumlah()
    {
        return $this->hasMany(Supply::class, 'satuan_jumlah_id');
    }

    public function suplyHarga()
    {
        return $this->hasMany(Supply::class, 'satuan_harga_id');
    }

    public function demandJumlah()
    {
        return $this->hasMany(Demand::class, 'satuan_jumlah_id');
    }

    public function demandHarga()
    {
        return $this->hasMany(Demand::class, 'satuan_harga_id');
    }
}
