<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'no_id',
        'kecamatan',
        'desa',
        'jumlah_penduduk',
        'status',
        'user_id',
        'created_by',
    ];

    /**
     * Relasi: satu survey punya banyak parcels (lahan)
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'survey_id');
    }

    /**
     * Relasi: satu survey punya banyak media (audio/photo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
