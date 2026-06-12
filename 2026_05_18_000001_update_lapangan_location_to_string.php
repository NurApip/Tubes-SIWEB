<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            // ubah/siapkan kolom lokasi string
            if (Schema::hasColumn('lapangan', 'lokasi_id')) {
                $table->dropColumn('lokasi_id');
            }

            $table->string('lokasi', 255)->default('Area Bandung Pusat')->after('tipe_rumput');

            // pastikan kolom yang dipakai sudah ada (kalau environment kamu sebelumnya belum punya)
            if (!Schema::hasColumn('lapangan', 'harga_per_jam')) {
                $table->integer('harga_per_jam')->default(0)->after('lokasi');
            }
            if (!Schema::hasColumn('lapangan', 'foto')) {
                $table->string('foto')->nullable()->after('harga_per_jam');
            }
            if (!Schema::hasColumn('lapangan', 'fasilitas')) {
                $table->text('fasilitas')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('lapangan', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('fasilitas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (Schema::hasColumn('lapangan', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
            if (!Schema::hasColumn('lapangan', 'lokasi_id')) {
                $table->unsignedBigInteger('lokasi_id')->nullable()->after('tipe_rumput');
            }
        });
    }
};

