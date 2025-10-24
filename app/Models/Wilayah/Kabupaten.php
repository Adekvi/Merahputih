<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $guarded = [];

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'id_kabupaten', 'id');
    }
}
