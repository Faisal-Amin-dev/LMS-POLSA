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
        // Tabel Mahasiswa
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Tambah prodi_id kalau belum ada
            if (!Schema::hasColumn('mahasiswa', 'prodi_id')) {
                $table->foreignId('prodi_id')->nullable()->after('id')->constrained('prodis')->onDelete('set null');
            }
            
            // Hapus prodi hanya kalau masih ada
            if (Schema::hasColumn('mahasiswa', 'prodi')) {
                $table->dropColumn('prodi');
            }
        });

        // Tabel Dosen
        Schema::table('dosens', function (Blueprint $table) {
            // Tambah prodi_id kalau belum ada
            if (!Schema::hasColumn('dosens', 'prodi_id')) {
                $table->foreignId('prodi_id')->nullable()->after('id')->constrained('prodis')->onDelete('set null');
            }

            // Hapus prodi hanya kalau masih ada
            if (Schema::hasColumn('dosens', 'prodi')) {
                $table->dropColumn('prodi');
            }
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
