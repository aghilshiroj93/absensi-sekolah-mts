<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');

            $table->foreignId('id_jadwal')
                ->nullable()
                ->constrained('jadwal_guru', 'id_jadwal')
                ->onDelete('set null');

            $table->foreignId('id_siswa')
                ->nullable()
                ->constrained('siswa', 'id_siswa')
                ->onDelete('set null');

            $table->foreignId('id_kelas')
                ->references('id_kelas')
                ->on('kelas')
                ->onDelete('cascade');

            $table->string('jam_absen');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha', 'telat', 'hari_libur'])->default('hadir');

            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
