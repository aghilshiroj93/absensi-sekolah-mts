<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('absensi')->truncate();
        DB::table('siswa')->truncate();
        DB::table('kelas')->truncate();
        DB::table('guru')->truncate();
        DB::table('mapel')->truncate();
        DB::table('tahun_akademik')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed Tahun Akademik
        $tahunAkademik = [
            [
                'nama_tahun' => '2023/2024',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_tahun' => '2024/2025',
                'status' => 'tidak_aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('tahun_akademik')->insert($tahunAkademik);

        // Seed Users
        $users = [
            // Admin
            [
                'name' => 'Admin Sekolah',
                'email' => 'admin@sekolah.id',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Guru
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@sekolah.id',
                'username' => 'budi.santoso',
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ani Wijaya',
                'email' => 'ani.wijaya@sekolah.id',
                'username' => 'ani.wijaya',
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rina Dewi',
                'email' => 'rina.dewi@sekolah.id',
                'username' => 'rina.dewi',
                'password' => Hash::make('password123'),
                'role' => 'guru_bk',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Siswa
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@siswa.id',
                'username' => 'andi.pratama',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@siswa.id',
                'username' => 'siti.rahayu',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Orang Tua
            [
                'name' => 'Bapak Andi',
                'email' => 'bapak.andi@ortu.id',
                'username' => 'bapak.andi',
                'password' => Hash::make('password123'),
                'role' => 'orang_tua',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('users')->insert($users);

        // Seed Mapel
        $mapel = [
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'MAT-10',
                'deskripsi' => 'Pelajaran matematika dasar',
                'id_tahun_akademik' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'BIN-10',
                'deskripsi' => 'Pelajaran bahasa Indonesia',
                'id_tahun_akademik' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'BIG-10',
                'deskripsi' => 'Pelajaran bahasa Inggris',
                'id_tahun_akademik' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_mapel' => 'Fisika',
                'kode_mapel' => 'FIS-10',
                'deskripsi' => 'Pelajaran fisika dasar',
                'id_tahun_akademik' => 1,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('mapel')->insert($mapel);

        // Seed Guru
        $guru = [
            [
                'nip' => '198003012003121001',
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'budi.santoso@sekolah.id',
                'id_user' => 2,
                'id_mapel' => 1, // Matematika
                'foto' => null,
                'jenis_kelamin' => 'L',
                'jabatan' => 'guru_pengajar',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Pendidikan No. 123, Jakarta',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nip' => '198104022003122002',
                'nama_lengkap' => 'Ani Wijaya',
                'email' => 'ani.wijaya@sekolah.id',
                'id_user' => 3,
                'id_mapel' => 2, // Bahasa Indonesia
                'foto' => null,
                'jenis_kelamin' => 'P',
                'jabatan' => 'guru_pengajar',
                'nomor_telepon' => '081234567891',
                'alamat' => 'Jl. Guru No. 45, Jakarta',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nip' => '197905152005122003',
                'nama_lengkap' => 'Rina Dewi',
                'email' => 'rina.dewi@sekolah.id',
                'id_user' => 4,
                'id_mapel' => null,
                'foto' => null,
                'jenis_kelamin' => 'P',
                'jabatan' => 'guru_bk',
                'nomor_telepon' => '081234567892',
                'alamat' => 'Jl. Bimbingan No. 67, Jakarta',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('guru')->insert($guru);

        // Seed Kelas
        $kelas = [
            [
                'nama_kelas' => '10 IPA 1',
                'tingkat' => '10',
                'id_tahun_akademik' => 1,
                'id_guru_pengajar' => 1, // Budi Santoso
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kelas' => '10 IPA 2',
                'tingkat' => '10',
                'id_tahun_akademik' => 1,
                'id_guru_pengajar' => 2, // Ani Wijaya
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kelas' => '10 IPS 1',
                'tingkat' => '10',
                'id_tahun_akademik' => 1,
                'id_guru_pengajar' => 1, // Budi Santoso
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('kelas')->insert($kelas);

        // Seed Siswa
        $siswa = [
            [
                'nis' => '202310001',
                'id_user' => 5,
                'nama_lengkap' => 'Andi Pratama',
                'id_kelas' => 1, // 10 IPA 1
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2007-05-15',
                'tempat_lahir' => 'Jakarta',
                'umur' => '16',
                'nomor_telepon' => '081234567893',
                'foto' => null,
                'alamat' => 'Jl. Siswa No. 1, Jakarta',
                'nama_ayah' => 'Budi Pratama',
                'nama_ibu' => 'Siti Pratama',
                'nomor_telepon_ayah' => '081234567894',
                'nomor_telepon_ibu' => '081234567895',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nis' => '202310002',
                'id_user' => 6,
                'nama_lengkap' => 'Siti Rahayu',
                'id_kelas' => 1, // 10 IPA 1
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2007-06-20',
                'tempat_lahir' => 'Jakarta',
                'umur' => '16',
                'nomor_telepon' => '081234567896',
                'foto' => null,
                'alamat' => 'Jl. Siswa No. 2, Jakarta',
                'nama_ayah' => 'Joko Rahayu',
                'nama_ibu' => 'Dewi Rahayu',
                'nomor_telepon_ayah' => '081234567897',
                'nomor_telepon_ibu' => '081234567898',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nis' => '202310003',
                'id_user' => null,
                'nama_lengkap' => 'Dewi Anggraeni',
                'id_kelas' => 2, // 10 IPA 2
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2007-04-10',
                'tempat_lahir' => 'Jakarta',
                'umur' => '16',
                'nomor_telepon' => '081234567899',
                'foto' => null,
                'alamat' => 'Jl. Siswa No. 3, Jakarta',
                'nama_ayah' => 'Agus Anggraeni',
                'nama_ibu' => 'Rini Anggraeni',
                'nomor_telepon_ayah' => '081234567900',
                'nomor_telepon_ibu' => '081234567901',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('siswa')->insert($siswa);

        // 7. Jadwal Guru
        DB::table('jadwal_guru')->insert([
            'id_tahun_akademik' => 1,
            'id_guru' => 1,
            'id_mapel' => 1,
            'id_kelas' => 1,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:40',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Absensi
        $absensi = [];
        $statuses = ['hadir', 'izin', 'sakit', 'alpha', 'terlambat'];
        $today = Carbon::today();

        // Buat data absensi untuk 30 hari terakhir
        DB::table('absensi')->insert([
            'id_jadwal' => 1,
            'id_siswa' => 1,
            'id_kelas' => 1,
            'jam_absen' => '07:05',
            'status' => 'hadir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
