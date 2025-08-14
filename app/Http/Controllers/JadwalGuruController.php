<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalGuru;
use App\Models\Guru;
use App\Models\IsAdmin;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tahun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalGuruController extends Controller
{
    public function index()
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $jadwal = JadwalGuru::with(['guru', 'mapel', 'kelas'])->get();
        return view('pages.jadwalguru.daftar', [
            "title" => "Daftar Jadwal Guru",
            "titlepage" => "Daftar Jadwal Guru",
            "jadwal" => $jadwal
        ]);
    }

    public function jadwalSaya()
    {
        $verifikasiGuru = new IsAdmin();
        $verifikasiGuru->isGuru();

        $guruId = auth()->user()->guru->id_guru; // sesuaikan dengan kolom id guru di tabel guru

        $jadwal = JadwalGuru::with(['guru', 'mapel', 'kelas'])
            ->where('id_guru', $guruId)
            ->get();

        return view('pages.jadwalguru.jadwalsaya', [
            "title" => "Jadwal Saya",
            "titlepage" => "Jadwal Saya",
            "jadwal" => $jadwal
        ]);
    }

    public function detailJadwal(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        // $verifikasiAdmin = new IsAdmin();
        // $verifikasiAdmin->isAdmin();


        $tanggalHariIni = Carbon::now()->format('d-m-Y');
        $hariIni = Carbon::now()->translatedFormat('l');

        $absensi = Absensi::where('id_kelas', $request->id)->get();
        // $absensi = Absensi::with(['siswa', 'mapel.kelas', 'kelas.tahun'])
        //     ->where('id_kelas', $request->id)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        // $mapel = Mapel::where('id_guru', $request->id)->first();
        $verifikasiTanggalHariIni = Carbon::now()->format('Y-m-d');

        $kelas = Kelas::where('id_kelas', $request->id)->first();
        $guru = Guru::with('mapel')->where('id_guru', $kelas->id_guru_pengajar)->first();
        $murid = Siswa::with(['kelas', 'kelas.tahun'])->where('id_kelas', $request->id)->orderBy('nama_lengkap')->get();

        $data = [
            "title" => "Detail Jadwal",
            "titlepage" => "Detail Jadwal",
            "tanggal" => $tanggalHariIni,
            "hari" => $hariIni,
            "absensi" => $absensi,
            "verifikasiTanggalHariIni" => $verifikasiTanggalHariIni,
            "kelas" => $kelas,
            "murid" => $murid,
            "guru" => $guru,

        ];
        // return $data;
        return view('pages.jadwalguru.detailjadwal', $data);
    }

    public function createAbsensi(Request $request)
    {
        Log::info('Memulai proses absensi', ['data_request' => $request->all()]);

        $data_nis = $request->absensi;

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $data_nis)->first();

        if (!$siswa) {
            Log::error('Siswa tidak ditemukan', ['nis' => $data_nis]);
            return response()->json(['message' => 'Terjadi kesalahan: Siswa tidak ditemukan.'], 400);
        }

        $idSiswa = $siswa->id_siswa;
        $idKelas = $siswa->id_kelas;

        Log::info('Data siswa ditemukan', [
            'id_siswa' => $idSiswa,
            'nama_siswa' => $siswa->nama_lengkap,
            'id_kelas' => $idKelas
        ]);

        $kelas = Kelas::where('id_kelas', $idKelas)->first();
        $guru = Guru::with('mapel')->where('id_guru', $kelas->id_guru_pengajar)->first();

        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal = JadwalGuru::where('id_kelas', $idKelas)
            ->where('id_guru', $guru->id_guru)
            ->where('id_mapel', $guru->id_mapel)
            ->where('hari', $hariIni)
            ->first();

        if (!$jadwal) {
            Log::error('Jadwal tidak ditemukan', [
                'id_kelas' => $idKelas,
                'id_guru' => $guru->id_guru,
                'hari' => $hariIni
            ]);
            return response()->json(['message' => 'Terjadi kesalahan: Jadwal tidak ditemukan untuk hari ini.'], 400);
        }

        Log::info('Jadwal ditemukan', [
            'jam_mulai' => $jadwal->jam_mulai,
            'jam_selesai' => $jadwal->jam_selesai,
            'mapel' => $guru->mapel->nama_mapel
        ]);

        // Ambil absensi terakhir siswa ini
        $lastAbsensi = Absensi::where('id_siswa', $idSiswa)
            ->whereDate('created_at', Carbon::today())
            ->first();

        // Cek apakah sudah absen hari ini
        if ($lastAbsensi) {
            Log::warning('Siswa sudah absen hari ini', [
                'id_siswa' => $idSiswa,
                'absensi_terakhir' => $lastAbsensi->created_at,
                'status' => $lastAbsensi->status
            ]);
            return response()->json(['message' => 'Terjadi kesalahan: Siswa sudah melakukan absensi hari ini.'], 400);
        }

        // Tentukan status dengan logika yang lebih jelas
        $jamMulai = Carbon::parse($jadwal->jam_mulai);
        $jamSelesai = Carbon::parse($jadwal->jam_selesai);
        $waktuSekarang = Carbon::now();

        // Batas toleransi keterlambatan (15 menit)
        $batasTerlambat = $jamMulai->copy()->addMinutes(15);

        Log::debug('Perbandingan waktu absensi', [
            'jam_mulai' => $jamMulai->format('H:i:s'),
            'jam_selesai' => $jamSelesai->format('H:i:s'),
            'waktu_absen' => $waktuSekarang->format('H:i:s'),
            'batas_terlambat' => $batasTerlambat->format('H:i:s')
        ]);

        // Default status adalah alpha (tidak hadir)
        $status = 'alpha';

        if ($waktuSekarang->between($jamMulai, $jamSelesai)) {
            if ($waktuSekarang->gt($batasTerlambat)) {
                $status = 'telat';
                Log::info('Status: Telat', ['waktu_absen' => $waktuSekarang]);
            } else {
                $status = 'hadir';
                Log::info('Status: Hadir', ['waktu_absen' => $waktuSekarang]);
            }
        } elseif ($waktuSekarang->lt($jamMulai)) {
            $status = 'alpha';
            Log::warning('Status: Alpha (absensi sebelum jam mulai)', ['waktu_absen' => $waktuSekarang]);
        } else {
            $status = 'alpha';
            Log::warning('Status: Alpha (absensi setelah jam selesai)', ['waktu_absen' => $waktuSekarang]);
        }

        // Simpan absensi
        $absensi = Absensi::create([
            'id_siswa' => $idSiswa,
            'id_kelas' => $idKelas,
            'id_jadwal' => $jadwal->id_jadwal,
            'jam_absen' => $waktuSekarang->format('H:i:s'),
            'status' => $status,
            'created_at' => $waktuSekarang,
            'updated_at' => $waktuSekarang
        ]);

        Log::info('Absensi berhasil disimpan', [
            'id_absensi' => $absensi->id_absensi,
            'status' => $status,
            'waktu_absen' => $absensi->jam_absen
        ]);

        $message = $this->getStatusMessage($status);

        return response()->json(['message' => $message], 200);
    }

    public function deleteAbsensi($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        Log::info('Absensi berhasil dihapus', ['id_absensi' => $id]);

        return redirect()->back()->with('success', 'Absensi berhasil dihapus.');
    }

    // Helper untuk mengembalikan pesan berdasarkan status
    private function getStatusMessage($status)
    {
        $messages = [
            'hadir' => 'Absensi berhasil. Silakan masuk kelas.',
            'telat' => 'Anda terlambat. Silakan masuk kelas.',
            'izin' => 'Absensi izin telah dicatat.',
            'sakit' => 'Absensi sakit telah dicatat.',
            'alpha' => 'Absensi diluar jam pelajaran dicatat sebagai tidak hadir.',
            'hari_libur' => 'Hari ini adalah hari libur.'
        ];

        return $messages[$status] ?? 'Absensi berhasil dicatat.';
    }

    public function monitoring()
    {
        $today = \Carbon\Carbon::today();

        $data = Absensi::with(['siswa', 'kelas', 'jadwal'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_absensi' => $item->id_absensi,
                    'nama_lengkap' => $item->siswa->nama_lengkap,
                    'nis' => $item->siswa->nis,
                    'nama_kelas' => $item->kelas->nama_kelas,
                    'jam_absen' => $item->jam_absen,
                    'status' => $item->status,
                ];
            });

        return response()->json($data);
    }

    public function create()
    {
        $guru = Guru::all();
        $mapel = Mapel::all();
        $kelas = Kelas::all();
        $tahun = Tahun::all();

        $data = [
            "title" => "Input Jadwal Guru",
            "titlepage" => "Input Jadwal Guru",
            "guru" => $guru,
            "mapel" => $mapel,
            "kelas" => $kelas,
            "tahun" => $tahun

        ];
        return view('pages.jadwalguru.input', $data);
    }

    public function getMapelKelas($id)
    {
        $guru = Guru::with(['mapel', 'kelasYangAjar'])->findOrFail($id);
        Log::debug("cek guru", [
            'guru' => $guru
        ]);

        return response()->json([
            'mapel' => $guru->mapel->map(function ($m) {
                return [
                    'id' => $m->id_mapel,
                    'nama' => $m->nama_mapel
                ];
            }),
            'kelas' => $guru->kelasYangAjar->map(function ($k) {
                return [
                    'id' => $k->id_kelas,
                    'nama' => $k->nama_kelas
                ];
            })
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_tahun_akademik' => 'required',
            'id_guru' => 'required',
            'id_mapel' => 'required',
            'id_kelas' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);
        Guru::findOrFail($request->id_guru)->update([
            'id_mapel' => $request->id_mapel
        ]);

        JadwalGuru::create($request->all());
        return redirect()->route('jadwalguru.index')->with('success', 'Jadwal guru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = JadwalGuru::findOrFail($id);
        $guru = Guru::all();
        $mapel = Mapel::all();
        $kelas = Kelas::all();
        // return view('pages.jadwalguru.edit', compact('jadwal', 'guru', 'mapel', 'kelas'));
        return view('pages.jadwalguru.edit', [
            "title" => "Edit Jadwal Guru",
            "titlepage" => "Edit Jadwal Guru",
            "guru" => $guru,
            "mapel" => $mapel,
            "kelas" => $kelas,
            "jadwalGuru" => $jadwal
        ]);
    }

    // public function edit($id)
    // {
    //     $jadwal = JadwalGuru::findOrFail($id);
    //     $guru = Guru::all();
    //     $tahun = Tahun::all();

    //     return view('pages.jadwalguru.edit', [
    //         "title" => "Edit Jadwal Guru",
    //         "titlepage" => "Edit Jadwal Guru",
    //         "guru" => $guru,
    //         "tahun" => $tahun,
    //         "jadwal" => $jadwal
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_guru' => 'required',
            'id_mapel' => 'required',
            'id_kelas' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        if ($request->id_mapel) {

            Guru::findOrFail($request->id_guru)->update([
                'id_mapel' => $request->id_mapel
            ]);
        }
        $jadwal = JadwalGuru::findOrFail($id);
        $jadwal->update($request->all());
        return redirect()->route('jadwalguru.index')->with('success', 'Jadwal guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        JadwalGuru::findOrFail($id)->delete();
        return redirect()->route('jadwalguru.index')->with('success', 'Jadwal guru berhasil dihapus');
    }
}
