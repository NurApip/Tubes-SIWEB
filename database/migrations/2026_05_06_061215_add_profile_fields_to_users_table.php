<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $row) {
            // Cek dulu apakah kolom 'hp' sudah ada (dari register awal) 
            // Jika belum ada 'no_hp', kita tambah. 
            if (!Schema::hasColumn('users', 'no_hp')) {
                $row->string('no_hp')->nullable()->after('email');
            }
            // Tambah kolom foto
            $row->string('foto')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $row) {
            $row->dropColumn(['no_hp', 'foto']);
        });
    }
};