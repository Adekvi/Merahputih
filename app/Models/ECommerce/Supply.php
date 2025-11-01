<?php

namespace App\Models\ECommerce;

use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use App\Models\ECommerce\Satuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id');
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
