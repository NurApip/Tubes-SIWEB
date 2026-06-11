<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahkan kolom lapangan_id untuk kebutuhan anti-bentrok
            if (!Schema::hasColumn('bookings', 'lapangan_id')) {
                $table->foreignId('lapangan_id')->constrained('lapangan', 'lapangan_id')->onDelete('cascade')->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'lapangan_id')) {
                $table->dropForeign(['lapangan_id']);
                $table->dropColumn('lapangan_id');
            }
        });
    }
};

