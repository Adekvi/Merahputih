<?php

namespace App\Models;

use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahLocation extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'wilayah_locations';

    /**
     * Kolom yang bisa diisi (mass assignable).
     */
    protected $fillable = [
        'kelurahan_id',
        'id_kecamatan',
        'nama_kelurahan',
        'latitude',
        'longitude',
        'provider',
        'raw_response',
    ];

    /**
     * Jika kamu punya relasi ke model Kecamatan.
     * Misal: 1 lokasi milik 1 kecamatan.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id');
    }

    /**
     * Jika kamu punya relasi ke model Kelurahan.
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }

    /**
     * Accessor: ambil lokasi dalam format [lat, lng].
     */
    public function getCoordsAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude,
            ];
        }
        return null;
    }

    /**
     * Mutator opsional: otomatis ubah nama_kelurahan jadi Title Case.
     */
    public function setNamaKelurahanAttribute($value)
    {
        $this->attributes['nama_kelurahan'] = ucwords(strtolower($value));
    }
}
