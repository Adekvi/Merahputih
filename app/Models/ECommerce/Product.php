<?php

namespace App\Models\Ecommerce;

use App\Models\ECommerce\Demand;
use App\Models\ECommerce\Satuan;
use App\Models\ECommerce\Supply;
use App\Models\User;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }

    public function suply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id');
    }

    public function satuanJumlah()
    {
        return $this->belongsTo(Satuan::class, 'satuan_jumlah_id');
    }

    public function satuanHarga()
    {
        return $this->belongsTo(Satuan::class, 'satuan_harga_id');
    }

}
