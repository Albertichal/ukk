<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'nis',
        'kelas_id',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function ruanganDiurus()
    {
        return $this->hasMany(Ruangan::class, 'penanggung_jawab_id');
    }

    public function inputAspirasi()
    {
        return $this->hasMany(InputAspirasi::class, 'user_id');
    }

    public function aspirasiDitugaskan()
    {
        return $this->hasMany(Aspirasi::class, 'assigned_to');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isPJ()
    {
        return $this->role === 'penanggung_jawab';
    }
}
