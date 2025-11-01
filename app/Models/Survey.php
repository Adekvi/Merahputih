<?php

namespace App\Models;

use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_id',
        'kecamatan_id',
        'kelurahan_id',
        'jumlah_penduduk',
        // jika pakai legacy fields: 'kecamatan', 'desa'
    ];

    protected $casts = [
        'jumlah_penduduk' => 'integer',
        'kecamatan_id' => 'integer',
        'kelurahan_id' => 'integer',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'survey_id', 'id');
    }

    // Robust helper to read nama kec/kel irrespective of column name
    protected function _resolveNama($model)
    {
        if (!$model) return null;
        foreach (['nama', 'nama_kecamatan', 'nama_kelurahan', 'nama_wilayah', 'name'] as $col) {
            if (isset($model->{$col}) && $model->{$col}) return $model->{$col};
        }
        // fallback: try casting model->toArray first key
        $arr = (array) $model->getAttributes();
        if (!empty($arr)) {
            $first = array_values($arr)[0] ?? null;
            return is_string($first) ? $first : null;
        }
        return null;
    }

    // Accessor: "Kecamatan - Kelurahan" (tolerant terhadap nama kolom)
    public function getWilayahAttribute()
    {
        $kec = $this->_resolveNama($this->kecamatan);
        $kel = $this->_resolveNama($this->kelurahan);

        if ($kec && $kel) return trim("{$kec} - {$kel}");
        return trim(($kec ?? $kel) ?? '');
    }
}
