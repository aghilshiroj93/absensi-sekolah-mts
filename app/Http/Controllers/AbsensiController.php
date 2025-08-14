<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Murid;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pages/scan', [
            "title" => "Scan QR",
            "titlepage" => "Scan QR"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Proses Absensi
     */
    public function store(Request $request)
    {
        $data_nis = $request->absensi;

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $data_nis)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Terjadi kesalahan: Siswa tidak ditemukan.'], 400);
        }

        $idSiswa = $siswa->id_siswa;
        $idKelas = $siswa->id_kelas;

        // Ambil absensi terakhir siswa ini
        $lastAbsensi = Absensi::where('id_siswa', $idSiswa)
            ->orderBy('created_at', 'desc')
            ->first();

        $today = Carbon::now()->format('Y-m-d');
        $jamSekarang = Carbon::now()->format('H:i:s');

        // Cek apakah sudah absen hari ini
        if ($lastAbsensi && $lastAbsensi->created_at->format('Y-m-d') === $today) {
            return response()->json(['message' => 'Terjadi kesalahan: Siswa sudah melakukan absensi hari ini.'], 400);
        }

        // Tentukan status
        if ($jamSekarang > '07:00:00') {
            $status = 'hadir'; // Bisa tambahkan kolom baru untuk keterlambatan jika mau
        } else {
            $status = 'hadir';
        }

        // Simpan absensi
        Absensi::create([
            'id_siswa' => $idSiswa,
            'id_kelas' => $idKelas,
            'jam_absen' => $jamSekarang,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Absensi berhasil. Silakan masuk kelas.'], 200);
    }

    /**
     * Generate absensi otomatis untuk siswa yang tidak hadir
     */
    public function gantiHari()
    {
        $today = Carbon::now()->format('Y-m-d');
        $jamSekarang = Carbon::now()->format('H:i:s');

        $siswaList = Siswa::all();

        foreach ($siswaList as $siswa) {
            $cekAbsen = Absensi::where('id_siswa', $siswa->id_siswa)
                ->whereDate('created_at', $today)
                ->first();

            if (!$cekAbsen) {
                Absensi::create([
                    'id_siswa' => $siswa->id_siswa,
                    'id_kelas' => $siswa->id_kelas,
                    'jam_absen' => $jamSekarang,
                    'status' => 'alpha', // Default jika tidak hadir
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return response()->json(['message' => 'Absensi otomatis berhasil dijalankan.']);
    }

    /**
     * Menampilkan absensi berdasarkan rentang tanggal
     */
    public function show_range(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $idSiswa = $request->input('id_siswa');

        $data = Absensi::where('id_siswa', $idSiswa)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return response()->json($data);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
    public function monitoring()
    {
        $today = \Carbon\Carbon::today();

        $data = DB::table('absensi')
            ->join('siswa', 'absensi.id_siswa', '=', 'siswa.id_siswa')
            ->join('kelas', 'absensi.id_kelas', '=', 'kelas.id_kelas')
            ->whereDate('absensi.created_at', $today)
            ->select('siswa.nama_lengkap', 'kelas.nama_kelas', 'absensi.jam_absen')
            ->orderBy('absensi.jam_absen', 'asc')
            ->get();

        return response()->json($data);
    }
}
