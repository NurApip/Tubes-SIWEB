<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangan', 'link_maps')) {
                $table->text('link_maps')->nullable()->after('alamat_lengkap');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (Schema::hasColumn('lapangan', 'link_maps')) {
                $table->dropColumn('link_maps');
            }
        });
    }
};
