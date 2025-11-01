<?php

namespace App\Models;

use App\Models\ECommerce\Demand;
use App\Models\ECommerce\Supply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'username', // ✅ ubah dari email ke username
        'password',
        'role',     // ✅ tambahkan role
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel Survey (satu user punya satu atau banyak survei)
     */
    public function surveys()
    {
        return $this->hasMany(Survey::class, 'user_id');
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function suply(){
        return $this->hasMany(Supply::class, 'user_id');
    }

    public function demand(){
        return $this->hasMany(Demand::class, 'user_id');
    }
}
