<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tahun;
use App\Models\IsAdmin;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

class TahunController extends Controller
{
    public function index()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $tahun = Tahun::orderBy('nama_tahun')->get();
        return view('pages/tahun/daftar', [
            "title" => "Daftar Tahun",
            "titlepage" => "Daftar Tahun",
            "tahun" => $tahun
        ]);
    }

    public function store(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();


        $tahun = new Tahun;
        $tahun->nama_tahun = $request->tahun;
        $tahun->created_at = now();
        $tahun->updated_at = now();
        $tahun->save();

        return redirect('/tahun')->with('success', 'Data Tahun Berhasil ditambahkan');
    }

    // public function destroy(Request $request)
    // {
    //     // Verifikasi untuk User yang login apakah dia Admin
    //     $verifikasiAdmin = new IsAdmin();
    //     $verifikasiAdmin->isAdmin();

    //     Log::debug("cek id tahun: " . $request->id);

    //     $getId = $request->id;

    //     try {
    //         $tahun = Tahun::findOrFail($getId);

    //         // Delete all related siswa (cascade will handle absensis)
    //         Siswa::where('id_tahun', $getId)->delete();

    //         // Delete the tahun
    //         $tahun->delete();

    //         return redirect('/tahun')->with('deleted', 'Tahun ajaran berhasil dihapus');
    //     } catch (\Exception $e) {
    //         return redirect('/tahun')->with('fail', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());
    //     }
    // }
    public function destroy(Tahun $tahun)
    {
        $tahun->delete();
        Log::debug("cek id tahun: " . $tahun->id_tahun_akademik);
        return redirect('/tahun')->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
