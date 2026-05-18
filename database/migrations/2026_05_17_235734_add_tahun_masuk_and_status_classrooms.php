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
        // Tambah tahun masuk di mahasiswa
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->integer('tahun_masuk')->nullable()->after('prodi_id');
        });

        // Tambah status aktif/arsip di classrooms
        Schema::table('classrooms', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'arsip'])->default('aktif')->after('tahun_akademik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
