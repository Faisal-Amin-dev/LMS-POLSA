<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Kita tambahkan relasi ke kurikulum dan kolom SKS jika belum ada
            if (!Schema::hasColumn('courses', 'kurikulum_id')) {
                $table->foreignId('kurikulum_id')->nullable()->after('id')->constrained('kurikulums')->onDelete('cascade');
            }
            if (!Schema::hasColumn('courses', 'sks')) {
                $table->integer('sks')->default(2)->after('nama_mk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['kurikulum_id']);
            $table->dropColumn(['kurikulum_id', 'sks']);
        });
    }
};