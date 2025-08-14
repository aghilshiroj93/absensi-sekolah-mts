<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tahun extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id_tahun_akademik'];
    protected $table = 'tahun_akademik';
    protected $primaryKey = 'id_tahun_akademik';
    public $timestamps = true;


    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
