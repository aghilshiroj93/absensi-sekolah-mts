<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\Absensi;
use App\Models\ManajemenWaktu;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $manajemenWaktu = new ManajemenWaktu();
        $tanggalHariIni = $manajemenWaktu->ambilTanggal();
        $hariIni = $manajemenWaktu->ambilHari();
        $bulanHariIni = $manajemenWaktu->ambilBulan();
        $tahunHariIni = $manajemenWaktu->ambilTahun();
        $waktuDatabase = $manajemenWaktu->ambilTahunBulanTanggal();

        $totalMurid = count(Siswa::all());

        // $dataAbsenMasuk = Absensi::with(['siswa', 'kelas'])->where('status', 'hadir')->whereDate('created_at', $waktuDatabase)->get();
        $dataAbsenMasuk = Absensi::with(['siswa', 'kelas'])->get();

        $dataAbsenAlpa = Absensi::with(['siswa', 'kelas'])->whereIn('status', ['tidak_hadir', 'alpha'])->get();
        $dataAbsenTerlambat = Absensi::with(['siswa', 'kelas'])->where('status', 'terlambat')->get();

        $absenAlpa = count($dataAbsenAlpa);
        $absenMasuk = count($dataAbsenMasuk);
        $absenTerlambat = count($dataAbsenTerlambat);

        $data = [

            "title" => "Beranda",
            "titlepage" => "Beranda",
            "absenMasuk" => $absenMasuk,
            "absenTerlambat" => $absenTerlambat,
            "absenAlpa" => $absenAlpa,
            "muridMasuk" => $dataAbsenMasuk,
            "muridTerlambat" => $dataAbsenTerlambat,
            "muridAlpa" => $dataAbsenAlpa,
            "totalMurid" => $totalMurid,
            "hari" => $hariIni,
            "tanggal" => $tanggalHariIni,
            "bulan" => $bulanHariIni,
            "tahun" => $tahunHariIni
        ];
        // return $data;
        return view('pages/beranda', $data);
    }
}
