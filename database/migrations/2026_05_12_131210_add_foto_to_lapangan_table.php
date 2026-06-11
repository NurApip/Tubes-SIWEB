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
    Schema::table('lapangan', function (Blueprint $table) {
        // Kita ganti 'lokasi' menjadi 'lokasi_id' sesuai isi database kamu
        $table->string('foto')->nullable()->after('lokasi_id');
    });
}

public function down(): void
{
    Schema::table('lapangan', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
}
};