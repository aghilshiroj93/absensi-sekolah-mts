<?php

namespace App\Http\Controllers;

use App\Models\IsAdmin;
use App\Models\Mapel;
use App\Models\Tahun;
use Illuminate\Http\Request;

class MapelController extends Controller
{

    public function index()
    {

        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $mapel = Mapel::with('tahunAkademik')->orderBy('nama_mapel')->get();

        return view('pages/mapel/daftar', [
            "title" => "Daftar Mata Pelajaran",
            "titlepage" => "Daftar Mata Pelajaran",
            "mapel" => $mapel
        ]);
    }

    public function create()
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $tahunAkademik = Tahun::orderBy('nama_tahun')->get();

        return view('pages/mapel/input', [
            "title" => "Input Mata Pelajaran",
            "titlepage" => "Input Mata Pelajaran",
            "tahunAkademik" => $tahunAkademik
        ]);
    }

    public function store(Request $request)
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $mapel = new Mapel;
        $mapel->nama_mapel = $request->nama_mapel;
        $mapel->kode_mapel = $request->kode_mapel;
        $mapel->deskripsi = $request->deskripsi;
        $mapel->id_tahun_akademik = $request->id_tahun_akademik;
        $mapel->status = $request->status;
        $mapel->created_at = now();
        $mapel->updated_at = now();
        $mapel->save();

        return redirect('/mapel')->with('success', 'Data Mata Pelajaran Berhasil ditambahkan');
    }

    public function destroy(Request $request)
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $getId = $request->id;

        try {
            $mapel = Mapel::findOrFail($getId);
            $mapel->delete();

            return redirect('/mapel')->with('success', 'Data Mata Pelajaran Berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/mapel')->with('error', 'Data Mata Pelajaran gagal dihapus: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $mapel = Mapel::findOrFail($id);
        $tahunAkademik = Tahun::orderBy('nama_tahun')->get();

        return view('pages/mapel/edit', [
            "title" => "Edit Mata Pelajaran",
            "titlepage" => "Edit Mata Pelajaran",
            "mapel" => $mapel,
            "tahunAkademik" => $tahunAkademik
        ]);
    }

    public function update(Request $request, $id)
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $mapel = Mapel::findOrFail($id);
        $mapel->nama_mapel = $request->nama_mapel;
        $mapel->kode_mapel = $request->kode_mapel;
        $mapel->deskripsi = $request->deskripsi;
        $mapel->id_tahun_akademik = $request->id_tahun_akademik;
        $mapel->status = $request->status;
        $mapel->updated_at = now();
        $mapel->save();

        return redirect('/mapel')->with('success', 'Data Mata Pelajaran Berhasil diubah');
    }
}
