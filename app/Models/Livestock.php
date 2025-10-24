<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
       use HasFactory;

    protected $table = 'livestocks';

    protected $fillable = [
        'parcel_id',
        'jenis_ternak',
        'jumlah',
        'produksi',
    ];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class, 'parcel_id');
    }
}
