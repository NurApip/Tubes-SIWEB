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
    Schema::create('foto_lapangans', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel lapangan (sesuai PK kamu yaitu lapangan_id)
        $table->foreignId('lapangan_id')->constrained('lapangan', 'lapangan_id')->onDelete('cascade');
        $table->string('path_foto'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_lapangans');
    }
};
