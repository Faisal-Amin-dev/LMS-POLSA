<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah classroom_id ke tabel berkas materi
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->after('id')->constrained('classrooms')->onDelete('cascade');
        });

        // 2. Tambah classroom_id ke tabel pengumuman
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->after('id')->constrained('classrooms')->onDelete('cascade');
        });

        // 3. Tambah classroom_id ke tabel tugas praktikum
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->after('id')->constrained('classrooms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });
    }
};