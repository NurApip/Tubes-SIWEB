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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'kode_tiket')) {
                $table->string('kode_tiket')->nullable()->after('status')->unique(false);
            }
            if (!Schema::hasColumn('bookings', 'durasi_bermain')) {
                $table->integer('durasi_bermain')->nullable()->after('durasi');
            }
            if (!Schema::hasColumn('bookings', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('nomor_wa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'kode_tiket')) $table->dropColumn('kode_tiket');
            if (Schema::hasColumn('bookings', 'durasi_bermain')) $table->dropColumn('durasi_bermain');
            if (Schema::hasColumn('bookings', 'no_hp')) $table->dropColumn('no_hp');
        });
    }
};
