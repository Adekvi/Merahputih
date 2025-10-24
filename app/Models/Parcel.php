<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected $table = 'parcels';

    protected $fillable = [
        'survey_id',
        'type',
        'area_hectare',
        'keterangan',
    ];

    protected $casts = [
        'area_hectare' => 'float',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function crops()
    {
        return $this->hasMany(ParcelCrop::class, 'parcel_id');
    }

    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'parcel_id');
    }
}
