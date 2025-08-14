<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Tahun;
use App\Models\IsAdmin;
use App\Models\Siswa;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MuridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_input()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $tahun = Tahun::orderBy('nama_tahun')->get();
        return view('pages/murid/input', [
            "title" => "Input Murid",
            "titlepage" => "Input Murid",
            "kelas" => $kelas,
            "tahun" => $tahun
        ]);
    }

    public function index_daftar()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $murid = Siswa::with(['kelas', 'kelas.tahun'])->get();
        // return $murid;
        return view('pages/murid/daftar', [
            "title" => "Daftar Murid",
            "titlepage" => "Daftar Murid",
            "murid" => $murid
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new IsAdmin())->isAdmin();

        $validated = $request->validate([
            'nis' => 'required|string|unique:siswa,nis',
            'nama_lengkap' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:255',
            'umur' => 'nullable|string|max:10',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'nomor_telepon_ayah' => 'nullable|string|max:20',
            'nomor_telepon_ibu' => 'nullable|string|max:20',
            'barcode' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('murid', 'public');
        }

        Siswa::create($validated);

        return redirect()->route('murid.input')->with('success', 'Data murid berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Siswa $murid) {}

    public function show_detail(Siswa $murid)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        // $verifikasiAdmin = new IsAdmin();
        // $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $kelas = Kelas::all();  // Get all columns
        $tahun = Tahun::all();
        $absensi = Absensi::latest()->where('id_siswa', $murid->id_siswa)->limit(30)->get();

        $data = [
            "title" => "Detail Murid",
            "titlepage" => "Detail Murid",
            "kelas" => $kelas,
            "murid" => $murid,
            "tahun" => $tahun,
            "absensi" => $absensi,
            "qr" => QrCode::size(200)->generate($murid->nis)
        ];
        // return $kelas;
        // return $absensi;  // Get all columns
        return view('pages/murid/detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $murid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $murid)
    {
        // Debug: log all request data
        Log::info('Edit Murid Request', $request->all());

        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $validated = $request->validate([
            'nama' => 'required|min:3|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_id' => 'required|exists:tahuns,id', // Fixed: table name is tahuns
        ]);

        try {
            $murid->nama = $validated['nama'];
            $murid->kelas_id = $validated['kelas_id'];
            $murid->tahun_id = $validated['tahun_id'];
            $murid->save();
            return redirect()->back()->with('success', 'Data murid berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Gagal mengubah data murid: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $getId = $request->murid;

        try {
            $murid = Siswa::findOrFail($getId);

            // Delete related absensis records first (although cascade should handle this)
            Absensi::where('id_siswa', $getId)->delete();

            // Delete the murid
            $murid->delete();

            return redirect('/daftar-murid')->with('deleted', 'Data Murid berhasil di hapus!');
        } catch (\Exception $e) {
            return redirect('/detail-murid/' . $getId)->with('fail', 'Gagal menghapus data murid: ' . $e->getMessage());
        }
    }

    public function deleteMurid(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $getId = $request->id;

        try {
            $murid = Siswa::findOrFail($getId);

            // Delete related absensis records first (although cascade should handle this)
            Absensi::where('id_siswa', $getId)->delete();

            // Delete the murid
            $murid->delete();

            return redirect('/daftar-murid')->with('deleted', 'Data Murid berhasil di hapus!');
        } catch (\Exception $e) {
            return redirect('/detail-murid/' . $getId)->with('fail', 'Gagal menghapus data murid: ' . $e->getMessage());
        }
    }
}
