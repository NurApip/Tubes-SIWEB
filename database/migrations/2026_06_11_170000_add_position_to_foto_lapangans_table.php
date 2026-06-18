<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foto_lapangans', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->nullable()->after('lapangan_id');
        });

        $lapanganIds = DB::table('foto_lapangans')
            ->distinct()
            ->pluck('lapangan_id');

        foreach ($lapanganIds as $lapanganId) {
            $photos = DB::table('foto_lapangans')
                ->where('lapangan_id', $lapanganId)
                ->orderBy('id')
                ->limit(4)
                ->get();

            foreach ($photos as $index => $photo) {
                DB::table('foto_lapangans')
                    ->where('id', $photo->id)
                    ->update(['position' => $index + 1]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('foto_lapangans', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
