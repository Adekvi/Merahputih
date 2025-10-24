<?php

namespace App\Models\Wilayah;

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
}
