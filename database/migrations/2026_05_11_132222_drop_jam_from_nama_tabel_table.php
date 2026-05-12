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
        Schema::table('classrooms', function (Blueprint $table) {
            // Hapus semua kolom yang berhubungan dengan kuliah fisik
            $table->dropColumn(['jam_mulai', 'jam_selesai']); 
        });
    }

    public function down(): void
    {
        // Ini untuk jaga-jaga kalau mau di-rollback (dikembalikan)
        Schema::table('classrooms', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
        });
    }
};
