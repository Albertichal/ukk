<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    public $timestamps = false;

    protected $fillable = ['nama_ruangan', 'penanggung_jawab_id'];

    public function penanggungjawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    public function inputAspirasi()
    {
        return $this->hasMany(InputAspirasi::class, 'ruangan_id', 'id_ruangan');
    }
}
