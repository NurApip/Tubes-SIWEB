<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangan', 'is_active')) {
                // default aktif
                $table->boolean('is_active')->default(true)->after('deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (Schema::hasColumn('lapangan', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};

