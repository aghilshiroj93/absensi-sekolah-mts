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
        Schema::create('jadwal_guru', function (Blueprint $table) {
            $table->id('id_jadwal');

            $table->foreignId('id_guru')
                ->constrained('guru', 'id_guru')
                ->onDelete('cascade');

            $table->foreignId('id_mapel')
                ->constrained('mapel', 'id_mapel')
                ->onDelete('cascade');

            $table->foreignId('id_kelas')
                ->constrained('kelas', 'id_kelas')
                ->onDelete('cascade');

            $table->foreignId('id_tahun_akademik')
                ->constrained('tahun_akademik', 'id_tahun_akademik')
                ->onDelete('cascade');

            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ]);

            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');

            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_gurus');
    }
};
