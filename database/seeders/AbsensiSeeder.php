<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Murid;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        $murids = Murid::all();
        $startDate = Carbon::now()->subMonth()->startOfMonth();  // Awal bulan lalu
        $endDate = Carbon::now()->subMonth()->endOfMonth();      // Akhir bulan lalu

        while ($startDate <= $endDate) {
            if ($startDate->isWeekend()) {
                $startDate->addDay();
                continue; // Lewati Sabtu & Minggu
            }

            foreach ($murids as $murid) {
                // Tentukan status kehadiran
                $chance = rand(1, 100);
                if ($chance <= 80) {
                    $status = 1; // Masuk
                } elseif ($chance <= 90) {
                    $status = 2; // Terlambat
                } else {
                    $status = 3; // Tidak masuk
                }

                // Buat jam absen berdasarkan status
                if ($status == 1) {
                    $jamAbsen = $startDate->copy()->setTime(rand(6, 7), rand(30, 59));
                } elseif ($status == 2) {
                    $jamAbsen = $startDate->copy()->setTime(8, rand(0, 30));
                } else {
                    $jamAbsen = $startDate->copy()->setTime(15, 0); // Tidak masuk tetap isi jam
                }

                // Simpan ke tabel absensis
                Absensi::create([
                    'murid_id'   => $murid->id,
                    'kelas_id'   => $murid->kelas_id,
                    'hari'       => $startDate->format('l'),
                    'tanggal'    => $startDate->format('d'),
                    'bulan'      => $startDate->format('F'),
                    'jam_absen'  => $jamAbsen->format('H:i:s'),
                    'status'     => $status,
                    'created_at' => $jamAbsen,
                    'updated_at' => $jamAbsen,
                ]);
            }

            $startDate->addDay();
        }
    }
}
