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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->string('nama_kelas'); // Contoh: TI 4A, TI 4B
            $table->string('hari');       // Senin, Selasa, dst
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('kode_ruangan')->nullable();
            $table->string('tahun_akademik'); // Contoh: 2025/2026 Ganjil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
