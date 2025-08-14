<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    protected $guarded = ['id_guru'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }


    public function kelasYangAjar()
    {
        return $this->hasMany(Kelas::class, 'id_guru_pengajar', 'id_guru');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_guru', 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'id_guru', 'id_mapel');
    }




    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }



    // public function getJabatanLengkapAttribute()
    // {
    //     $jabatan = [
    //         'guru_pengajar' => 'Guru Pengajar',
    //         'wali_kelas' => 'Wali Kelas',
    //         'guru_bk' => 'Guru BK',
    //         'kepala_sekolah' => 'Kepala Sekolah'
    //     ];

    //     return $jabatan[$this->jabatan] ?? 'Tidak Diketahui';
    // }

    // public function canEditLaporan($laporan)
    // {
    //     return $this->id_guru == $laporan->id_guru_pelapor ||
    //         in_array($this->jabatan, ['guru_bk', 'kepala_sekolah']);
    // }
}
