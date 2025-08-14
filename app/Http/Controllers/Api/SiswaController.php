<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;

class SiswaController extends Controller
{

    protected $siswa, $kelas;
    public function __construct(Kelas $kelas, Siswa $siswa)
    {
        $this->siswa = $siswa;
        $this->kelas = $kelas;
    }

    public function index()
    {
        $siswa = $this->siswa->where('status', 'tidak')
            ->with(['kelas', 'user'])
            ->orderBy('nama_lengkap', 'asc')
            ->get();
        if ($siswa->isEmpty()) {
            return response()->json([
                'success' => false,
                'code'  => 404,
                'message' => 'Data siswa tidak ditemukan.',
                'data' => []
            ]);
        }
        return response()->json([
            'success' => true,
            'code'  => 200,
            'message' => 'Data Siswa Berhasil ditampilkan',
            'data' => $siswa
        ], 200);
    }
}
