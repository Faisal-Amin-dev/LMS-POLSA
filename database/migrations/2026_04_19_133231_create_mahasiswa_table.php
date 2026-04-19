<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel users (untuk login)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->string('prodi')->default('Teknik Informatika');
            $table->string('kelas', 10); // Contoh: TI-4A
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
