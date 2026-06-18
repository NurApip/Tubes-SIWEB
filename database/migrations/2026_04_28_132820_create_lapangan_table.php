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
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id('lapangan_id'); // Menggunakan nama ID sesuai ERD
            $table->unsignedBigInteger('lokasi_id'); 
            $table->string('nama_lapangan');
            $table->string('tipe_rumput');
            $table->integer('harga_per_jam');
            $table->text('fasilitas');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};
