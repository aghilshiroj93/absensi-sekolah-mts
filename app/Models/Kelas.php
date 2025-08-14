<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $guarded = ['id_kelas'];
    public $timestamps = false;



    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }


    public function guruPengajar()
    {
        return $this->belongsTo(Guru::class, 'id_guru_pengajar', 'id_guru');
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'id_tahun_akademik', 'id_tahun_akademik');
    }
}
