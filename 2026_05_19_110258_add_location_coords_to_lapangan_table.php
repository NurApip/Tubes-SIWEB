<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangan', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable()->after('lokasi');
            }
            if (!Schema::hasColumn('lapangan', 'latitude')) {
                $table->string('latitude', 50)->nullable()->after('alamat_lengkap');
            }
            if (!Schema::hasColumn('lapangan', 'longitude')) {
                $table->string('longitude', 50)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            foreach (['alamat_lengkap', 'latitude', 'longitude'] as $col) {
                if (Schema::hasColumn('lapangan', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
