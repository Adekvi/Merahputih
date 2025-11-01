<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'type',
        'area_hectare',
        'keterangan',
    ];

    protected $casts = [
        'area_hectare' => 'float',
        'survey_id' => 'integer',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }

    public function crops()
    {
        return $this->hasMany(ParcelCrop::class, 'parcel_id', 'id');
    }

    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'parcel_id', 'id');
    }
}
