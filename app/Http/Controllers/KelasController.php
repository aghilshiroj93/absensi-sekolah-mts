<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Absensi;
use App\Models\IsAdmin;
use App\Models\Siswa;
use App\Models\Tahun;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $kelas = Kelas::all();

        $tahun = Tahun::orderBy('nama_tahun')->get();
        // return $tahun;
        return view('pages/kelas/daftar', [
            "title" => "Daftar Kelas",
            "titlepage" => "Daftar Kelas",
            "kelas" => $kelas,
            "tahunAkademik" => $tahun
        ]);
    }

    public function kelasSaya()
    {
        $verifikasiGuru = new IsAdmin();
        $verifikasiGuru->isGuru();

        $guruId = auth()->user()->guru->id_guru; // sesuaikan dengan kolom id guru di tabel guru


        $kelas = Kelas::with('guruPengajar.mapel', 'tahun')
            ->withCount('siswa')
            ->where('id_guru_pengajar', $guruId)
            ->orderBy('nama_kelas')
            ->get();


        // return $kelas;
        return view('pages.kelas.saya', [
            "title" => "Kelas Saya",
            "titlepage" => "Daftar Kelas Saya",
            "kelas" => $kelas,

        ]);
    }

    public function index_detail(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        // $verifikasiAdmin = new IsAdmin();
        // $verifikasiAdmin->isAdmin();


        $tanggalHariIni = Carbon::now()->format('d-m-Y');
        $hariIni = Carbon::now()->translatedFormat('l');

        $absensi = Absensi::with(['siswa', 'siswa.kelas', 'siswa.kelas.tahun'])->where('id_kelas', $request->id)->get();

        $verifikasiTanggalHariIni = Carbon::now()->format('Y-m-d');

        $kelas = Kelas::where('id_kelas', $request->id)->first();
        $murid = Siswa::with(['kelas', 'kelas.tahun'])->where('id_kelas', $request->id)->orderBy('nama_lengkap')->get();

        // return $absensi;
        return view('pages/kelas/detail', [
            "title" => "Detail Kelas $kelas->kelas",
            "titlepage" => "Detail Kelas : $kelas->kelas",
            "hari" => $hariIni,
            "tanggal" => $tanggalHariIni,
            "verifikasiWaktu" => $verifikasiTanggalHariIni,
            "kelas" => $kelas,
            "murid" => $murid,
            "absensi" => $absensi
        ]);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifikasi Admin
        (new IsAdmin())->isAdmin();

        $validated = $request->validate([
            'kelas' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kelas', 'nama_kelas'),
            ],
            'id_tahun_akademik' => ['required', 'integer', 'exists:tahun_akademik,id_tahun_akademik'],
        ]);

        $namaKelas = trim($validated['kelas']);

        // Cari tingkat di awal atau di mana saja di string (X, XI, XII atau 10/11/12)
        // contoh cocok: "XI IPS A", "XII-IPA 3", "10 IPA 1"
        $tingkat = null;
        if (preg_match('/\b(X|XI|XII|10|11|12)\b/i', $namaKelas, $m)) {
            $tingkat = strtoupper($m[0]);
            // Jika angka, konversi ke romawi agar konsisten (opsional)
            $map = ['10' => 'X', '11' => 'XI', '12' => 'XII'];
            if (isset($map[$tingkat])) {
                $tingkat = $map[$tingkat];
            }
        }

        if (!$tingkat) {
            return back()
                ->withErrors(['kelas' => 'Nama kelas harus mengandung tingkat (X, XI, XII atau 10, 11, 12). Contoh: "XI IPS A".'])
                ->withInput();
        }

        Kelas::create([
            'nama_kelas'        => $namaKelas,
            'tingkat'           => $tingkat, // otomatis dari regex di atas
            'id_tahun_akademik' => (int) $validated['id_tahun_akademik'],
            'id_guru_pengajar'  => null, // sementara dikosongkan
        ]);

        return redirect('/kelas/daftar')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function destroy(Request $request)
    {

        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $getId = $request->id;

        try {
            $kelas = Kelas::findOrFail($getId);

            Absensi::where('id_kelas', $getId)->delete();

            Siswa::where('id_kelas', $getId)->delete();

            // Delete the kelas
            $kelas->delete();

            return redirect('/kelas/daftar')->with('deleted', 'Kelas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/kelas/daftar')->with('fail', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }
}
