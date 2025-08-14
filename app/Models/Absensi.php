<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $guarded = ['id_absensi'];
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    public $timestamps = true;

    public function getTanggalVerifikasiFormattedAttribute()
    {
        return $this->created_at
            ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y')
            : null;
    }


    // Status constants
    const STATUS_MASUK = 1;
    const STATUS_TERLAMBAT = 2;
    const STATUS_TIDAK_MASUK = 3;

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalGuru::class, 'id_jadwal', 'id_jadwal');
    }
}
