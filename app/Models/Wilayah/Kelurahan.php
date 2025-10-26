<?php

namespace App\Models\Wilayah;

use App\Models\ECommerce\Demand;
use App\Models\ECommerce\Supply;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $guarded = [];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id');
    }

    public function suply()
    {
        return $this->hasMany(Supply::class, 'id_kelurahan', 'id');
    }

    public function demand()
    {
        return $this->hasMany(Demand::class, 'id_kelurahan', 'id');
    }
}
