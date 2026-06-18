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
        Schema::create('lapangan_operasional_today', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangan', 'lapangan_id')->onDelete('cascade');

            // slot mengikuti mapping UI user
            // Pagi  : 08:00 & 09:00
            // Siang : 16:00
            // Sore  : 18:00 - 20:00 (untuk UI user di kode kamu ini belum ada option jam 18:00/20:00; nanti dipetakan kalau diperlukan)
            // Malam : 19:00 & 20:00
            $table->string('slot'); // 'Pagi'|'Siang'|'Sore'|'Malam'

            // status operasional slot untuk hari ini
            $table->boolean('is_full')->default(false);

            $table->timestamps();

            $table->unique(['lapangan_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan_operasional_today');
    }
};
