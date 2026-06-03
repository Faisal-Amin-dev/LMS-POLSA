<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Mengikat kelas perkuliahan ke tahun ajaran spesifik (biar ga ada histori numpuk)
            if (!Schema::hasColumn('classrooms', 'tahun_ajaran_id')) {
                $table->foreignId('tahun_ajaran_id')->nullable()->after('id')->constrained('tahun_ajarans')->onDelete('cascade');
            }
            // Tempat Dosen menempelkan Dokumen Mutu Akademik (BPM & Kaprodi bisa monitoring)
            if (!Schema::hasColumn('classrooms', 'file_rps')) {
                $table->string('file_rps')->nullable()->after('nama_kelas');
                $table->text('kontrak_kuliah')->nullable()->after('file_rps');
            }
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn(['tahun_ajaran_id', 'file_rps', 'kontrak_kuliah']);
        });
    }
};