<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $guarded = [];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id');
    }
}
