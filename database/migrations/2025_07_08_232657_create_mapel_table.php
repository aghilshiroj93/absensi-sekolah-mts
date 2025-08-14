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
        Schema::create('mapel', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('nama_mapel');
            $table->string('kode_mapel');
            $table->text('deskripsi')->nullable();
            $table->foreignId('id_tahun_akademik')
                ->constrained('tahun_akademik', 'id_tahun_akademik')
                ->onDelete('cascade');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            // Tambahkan index unique composite
            $table->unique(['nama_mapel', 'deleted_at']);
            $table->unique(['kode_mapel', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};
