<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom course_id menjadi nullable (boleh kosong)
        Schema::table('announcements', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Dikembalikan jika di-rollback
        Schema::table('announcements', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });
    }
};