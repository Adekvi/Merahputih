<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelCrop extends Model
{
     use HasFactory;

    protected $table = 'parcel_crops';

    protected $fillable = [
        'parcel_id',
        'nama_tanaman',
        'luas_hektare',
        'produksi_ton',
        'satuan',
        'catatan',
    ];

    protected $casts = [
        'luas_hektare' => 'float',
        'produksi_ton' => 'float',
        'parcel_id' => 'integer',
    ];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class, 'parcel_id', 'id');
    }
}
