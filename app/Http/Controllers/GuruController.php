<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\IsAdmin;
use App\Models\IsAdmin as ModelsIsAdmin;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    public function index()
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        $guru = Guru::with(['mapel', 'user'])->orderBy('nama_lengkap')->get();

        // return $guru;
        return view('pages.guru.daftar', [
            "title" => "Daftar Guru",
            "titlepage" => "Daftar Guru",
            "guru" => $guru
        ]);
    }

    public function create()
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        // $users = User::doesntHave('guru')->get();
        $mapel = Mapel::orderBy('nama_mapel')->get();

        return view('pages.guru.input', [
            "title" => "Input Guru",
            "titlepage" => "Input Guru",
            // "users" => $users,
            "mapel" => $mapel
        ]);
    }

    public function store(Request $request)
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        $request->validate([
            'nip' => 'required|unique:guru,nip',
            'nama_lengkap' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:guru,email|unique:users,email',
            'id_mapel' => 'nullable|array',
            'id_mapel.*' => 'exists:mapel,id_mapel',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|in:guru_pengajar,guru_bk',
            'nomor_telepon' => 'required',
        ]);

        // Buat user untuk guru
        $createdUser = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'username' => $request->username,
            'role' => 'guru',
        ]);

        // Upload foto kalau ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
        }

        // Simpan data guru
        $createdGuru = Guru::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'id_user' => $createdUser->id,
            'foto' => $fotoPath,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jabatan' => $request->jabatan,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat
        ]);

        // Simpan relasi mapel (many-to-many)
        if ($request->has('id_mapel')) {
            $createdGuru->mapel()->sync($request->id_mapel);
        }

        Log::debug("cek guru", [
            'guru' => $createdGuru,
            'user' => $createdUser
        ]);

        return redirect('/guru')->with('success', 'Data Guru berhasil ditambahkan');
    }


    public function edit($id)
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        $guru = Guru::findOrFail($id);
        $users = User::all();
        $mapel = Mapel::orderBy('nama_mapel')->get();

        return view('pages.guru.edit', [
            "title" => "Edit Guru",
            "titlepage" => "Edit Guru",
            "guru" => $guru,
            "users" => $users,
            "mapel" => $mapel
        ]);
    }

    public function update(Request $request, $id)
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        $guru = Guru::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:guru,nip,' . $id . ',id_guru',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:guru,email,' . $id . ',id_guru',
            'id_user' => 'required|exists:users,id',
            'id_mapel' => 'nullable|array',
            'id_mapel.*' => 'exists:mapel,id_mapel',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|in:guru_pengajar,guru_bk',
            'nomor_telepon' => 'required',
        ]);

        // Foto lama
        $fotoPath = $guru->foto;

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
        }

        // Update data guru
        $guru->update([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'id_user' => $request->id_user,
            'foto' => $fotoPath,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jabatan' => $request->jabatan,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat
        ]);

        // Sinkronisasi mapel di pivot table
        if ($request->has('id_mapel')) {
            $guru->mapel()->sync($request->id_mapel);
        } else {
            $guru->mapel()->detach(); // kosongkan jika tidak ada mapel yang dipilih
        }

        return redirect('/guru')->with('success', 'Data Guru berhasil diubah');
    }


    public function destroy(Request $request)
    {
        $verifikasiAdmin = new ModelsIsAdmin();
        $verifikasiAdmin->isAdmin();

        try {
            $guru = Guru::findOrFail($request->id);
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $guru->delete();
            return redirect('/guru')->with('success', 'Data Guru berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/guru')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
