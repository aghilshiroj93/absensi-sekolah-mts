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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas'); // Contoh: "12 IPA 1"
            $table->string('tingkat');
            $table->foreignId('id_tahun_akademik')
                ->constrained('tahun_akademik', 'id_tahun_akademik')
                ->onDelete('cascade');
            $table->foreignId('id_guru_pengajar')
                ->nullable()
                ->constrained('guru', 'id_guru')
                ->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
