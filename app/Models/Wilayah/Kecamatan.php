<?php

namespace App\Models\Wilayah;

use App\Models\ECommerce\Supply;
use App\Models\ECommerce\Demand;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = [];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten', 'id');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id');
    }

    public function suply()
    {
        return $this->hasMany(Supply::class, 'id_kecamatan', 'id');
    }

    public function demand()
    {
        return $this->hasMany(Demand::class, 'id_kecamatan', 'id');
    }
}
