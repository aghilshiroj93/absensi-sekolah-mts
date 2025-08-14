<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GpsController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PengaturanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Menuju Halaman Login
Route::get('/', [LoginController::class, 'index'])->name('login');

// Proses Login
Route::post('/', [LoginController::class, 'authenticate']);

// Proses Logout
Route::post('/keluar', [LoginController::class, 'logout']);

// Menuju Halaman Beranda
Route::get('/beranda', [DashboardController::class, 'index'])->middleware('auth');

// // Menuju Halaman Scan QR
// Route::get('/scan-qr', [AbsensiController::class, 'index'])->middleware('auth');

// // Proses Scan QR
// Route::get('/scan-qr/{absensi}', [AbsensiController::class, 'store']);

// // Proses Absensi Otomatis Ganti Hari
// Route::get('/auto-run', [AbsensiController::class, 'gantiHari']);

Route::get('/scan-qr', [AbsensiController::class, 'index']);

// Proses hasil scan QR
// Route::post('/kirim/scan-qr', [AbsensiController::class, 'store']);

// Auto run ganti hari
Route::get('/auto-run', [AbsensiController::class, 'gantiHari']);

Route::get('/monitoring/absensi', [AbsensiController::class, 'monitoring'])->middleware('auth');
Route::post('/kirim/scan-qr', [AbsensiController::class, 'store'])->middleware('auth');




// Halaman Standalone-Scan
Route::get('/scan-qr-standalone', function () {

    return view('/pages/scan-standalone');
});


// Menuju Halaman Input Murid
Route::get('/input-murid', [MuridController::class, 'index_input'])->name('murid.input')->middleware('auth');

// Menyimpan Data Input Murid
Route::post('/input-murid-proses', [MuridController::class, 'store'])->middleware('auth');

// Menuju Halaman Daftar Murid
Route::get('/daftar-murid', [MuridController::class, 'index_daftar'])->middleware('auth');

// Menuju Halaman Daftar Murid
Route::get('/daftar-murid/json', [MuridController::class, 'data'])->middleware('auth');

// Fungsi Hapus Murid
// Route::post('/detail-murid/hapus/{murid}', [MuridController::class, 'destroy']);

// Menuju Halaman Detail Murid
Route::get('/detail-murid/{murid}', [MuridController::class, 'show_detail']);
Route::post('/daftar-murid/hapus/{murid}', [MuridController::class, 'deleteMurid'])->name('murid.destroy')->middleware('auth');

// // Fungsi Menampilkan Absensi Dengan Range Tertentu
// Route::post('/detail-murid/{id}', [AbsensiController::class, 'show_range'])->middleware('auth');

// Menuju Halaman Daftar Kelas
Route::get('/kelas/daftar', [KelasController::class, 'index'])->middleware('auth');

// Menuju Halaman Detail Kelas
Route::get('/kelas/daftar/{id}', [KelasController::class, 'index_detail'])->middleware('auth');

// Menuju Halaman Data Master Kelas
Route::get('/kelas', [KelasController::class, 'index_master'])->middleware('auth');

// Menyimpan Data Master Kelas
Route::post('/kelas-proses', [KelasController::class, 'store']);

// Hapus Kelas
Route::post('/kelas/hapus/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy')->middleware('auth');

// Menuju Halaman Data Master Tahun
Route::get('/tahun', [TahunController::class, 'index'])->middleware('auth');

// Menyimpan Data Master Tahun
Route::post('/tahun-proses', [TahunController::class, 'store']);
Route::delete('hapus-tahun/{tahun}', [TahunController::class, 'destroy'])->name('destroy');

// Untuk mendownload kartu absen siswa satuan
Route::get('/download-kartu-satuan/{murid:id}', [PdfController::class, 'downloadKartuSatuan'])->middleware('auth');

// Untuk mendownload kartu absen siswa secara massal per-kelas
Route::get('/download-kartu-massal/{kelas:id}', [PdfController::class, 'downloadKartuMassal'])->middleware('auth');

// Halaman Pengaturan
Route::get('/pengaturan', [PengaturanController::class, 'show'])->middleware('auth');

// Halaman Pengaturan
Route::post('/pengaturan', [PengaturanController::class, 'update'])->middleware('auth');

// Halaman GPS
Route::get('/gps', [GpsController::class, 'index'])->middleware('auth');

// API untuk mengedit informasi murid
Route::post('/edit-murid/{murid}', [MuridController::class, 'update'])->middleware('auth');

// Daftar Mapel
Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index')->middleware('auth');

// Form tambah mapel
Route::get('/mapel/create', [MapelController::class, 'create'])->name('mapel.create')->middleware('auth');
Route::post('/mapel/store', [MapelController::class, 'store'])->name('mapel.store')->middleware('auth');

// Hapus mapel
Route::post('/mapel/delete', [MapelController::class, 'destroy'])->name('mapel.destroy')->middleware('auth');

// Form edit mapel
Route::get('/mapel/{id}/edit', [MapelController::class, 'edit'])->name('mapel.edit')->middleware('auth');
Route::post('/mapel/update/{id}', [MapelController::class, 'update'])->name('mapel.update')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Guru Routes
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
    Route::post('/guru/store', [GuruController::class, 'store'])->name('guru.store');
    Route::get('/guru/{id}/edit', [GuruController::class, 'edit'])->name('guru.edit');
    Route::post('/guru/update/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::post('/guru/delete', [GuruController::class, 'destroy'])->name('guru.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Guru Routes
    Route::get('/jadwalguru', [JadwalGuruController::class, 'index'])->name('jadwalguru.index');
    Route::get('/jadwalguru/create', [JadwalGuruController::class, 'create'])->name('jadwalguru.create');
    Route::post('/jadwalguru/store', [JadwalGuruController::class, 'store'])->name('jadwalguru.store');
    Route::get('/jadwalguru/{id}/edit', [JadwalGuruController::class, 'edit'])->name('jadwalguru.edit');
    Route::post('/jadwalguru/update/{id}', [JadwalGuruController::class, 'update'])->name('jadwalguru.update');
    Route::post('/jadwalguru/delete/{id}', [JadwalGuruController::class, 'destroy'])->name('jadwalguru.destroy');
    Route::get('/jadwalsaya', [JadwalGuruController::class, 'jadwalSaya'])->name('jadwalsaya');
    Route::get('/jadwalguru/detail/{id}', [JadwalGuruController::class, 'detailJadwal'])->name('jadwalguru.detail');
    Route::get('/jadwalguru/monitoring', [JadwalGuruController::class, 'monitoring'])->name('jadwalguru.monitoring');
    Route::post('/jadwalguru/absensi', [JadwalGuruController::class, 'createAbsensi'])->name('jadwalguru.absensi');
    Route::post('/jadwalguru/absensi/{id}', [JadwalGuruController::class, 'deleteAbsensi'])->name('absensi.delete');
    Route::get('/guru/{id}/mapel-kelas', [JadwalGuruController::class, 'getMapelKelas'])->name('guru.mapel.kelas');
});

Route::middleware(['auth', 'can:guru'])->group(function () {
    Route::get('/kelas-saya', [KelasController::class, 'kelasSaya']);
});
