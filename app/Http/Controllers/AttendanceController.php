<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Generate barcode untuk employee
    public function generateBarcode($employeeId)
    {
        $employee = Siswa::findOrFail($employeeId);

        if (empty($employee->barcode)) {
            $barcode = 'EMP' . str_pad($employee->id, 6, '0', STR_PAD_LEFT);
            $employee->update(['barcode' => $barcode]);
        }

        $dns = new DNS1D();
        $barcodeImage = $dns->getBarcodePNG($employee->barcode, 'C128');

        return response($barcodeImage)->header('Content-type', 'image/png');
    }

    // Scan barcode untuk absensi
    public function scan(Request $request)
    {
        $request->validate([
            'nis' => 'required|string'
        ]);
        log('Scanning NIS: ' . $request->nis);

        $employee = Siswa::where('nis', $request->nis)->first();

        if (!$employee) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $now = Carbon::now();
        $today = $now->toDateString();

        // Cek apakah sudah check in hari ini
        $attendance = Absensi::where('id_siswa', $employee->id_siswa)
            ->whereDate('created_at', $today)
            ->first();

        // if ($attendance) {
        //     // Jika sudah check in, lakukan check out
        //     if (is_null($attendance->check_out)) {
        //         $attendance->update(['check_out' => $now]);
        //         return response()->json(['message' => 'Check out successful', 'type' => 'out']);
        //     }
        //     return response()->json(['error' => 'Already checked out today'], 400);
        // } else {
        // Jika belum check in, lakukan check in
        Absensi::create([
            'id_siswa' => $employee->id_siswa,
            'jam_absen' => $now,
            'status' => 'hadir',
            // 'status' => $this->determineStatus($now)
        ]);
        return response()->json(['message' => 'Check in successful', 'type' => 'in']);
        // }
    }

    protected function determineStatus($time)
    {
        $officeStartTime = Carbon::createFromTime(8, 0, 0); // Jam 8 pagi
        return $time->gt($officeStartTime) ? 'late' : 'present';
    }

    // Laporan absensi
    public function report()
    {
        $attendances = Absensi::with('siswa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attendance.report', compact('attendances'));
    }
}
