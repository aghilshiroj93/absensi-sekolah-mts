<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Murid;
use App\Models\Absensi;
use App\Models\ManajemenWaktu;
use Illuminate\Support\Carbon;
use Exception;

class MarkAbsentStudents extends Command
{
    protected $signature = 'absensi:mark-absent {--force : Force mark absences regardless of time}';
    protected $description = 'Mark students who have not scanned QR code as tidak masuk/izin';

    public function handle()
    {
        try {
            $today = Carbon::now();

            // Skip if it's weekend
            if ($today->isWeekend()) {
                $this->info('Skipping absence marking on weekend.');
                return;
            }

            // Check if we should run based on time unless --force is used
            if (!$this->option('force')) {
                $currentTime = $today->format('H:i');
                // Only run after school hours (15:00)
                if ($currentTime < '15:00') {
                    $this->info('Skipping absence marking before 15:00.');
                    return;
                }
            }

            $hariIni = $today->locale('id')->translatedFormat('l');
            $tanggalHariIni = $today->translatedFormat('d');
            $bulanHariIni = $today->locale('id')->translatedFormat('F');
            $jamHariIni = $today->format('H:i:s');

            // Get all active students
            $students = Murid::with('kelas')->get();
            $markedCount = 0;

            $this->output->progressStart(count($students));
            
            foreach ($students as $student) {
                // Check if student has already been marked for attendance today
                $hasAttendance = Absensi::where('murid_id', $student->id)
                    ->whereDate('created_at', $today->toDateString())
                    ->exists();
                
                // If student hasn't been marked, create an absence record
                if (!$hasAttendance) {
                    Absensi::create([
                        'murid_id' => $student->id,
                        'kelas_id' => $student->kelas_id,
                        'hari' => $hariIni,
                        'tanggal' => $tanggalHariIni,
                        'bulan' => $bulanHariIni,
                        'jam_absen' => $jamHariIni,
                        'status' => Absensi::STATUS_TIDAK_MASUK, // Using constant for better maintainability
                    ]);
                    $markedCount++;
                }
                
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("Successfully marked {$markedCount} students as absent.");
        } catch (Exception $e) {
            $this->error("Error marking absences: " . $e->getMessage());
            return 1;
        }
    }
}
