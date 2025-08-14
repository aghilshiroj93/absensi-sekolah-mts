<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';
    protected $guarded = ['id_mapel'];
    public $timestamps = true;


    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'id_mapel', 'id_guru');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_mapel', 'id_mapel');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(Tahun::class, 'id_tahun_akademik', 'id_tahun_akademik');
    }
}
