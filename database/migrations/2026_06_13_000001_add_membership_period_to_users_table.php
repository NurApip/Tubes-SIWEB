<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('membership_started_at')->nullable()->after('membership_requested_at');
            $table->timestamp('membership_expires_at')->nullable()->after('membership_started_at');
        });

        $startedAt = now();

        DB::table('users')
            ->where('is_member', 1)
            ->whereNull('membership_expires_at')
            ->update([
                'membership_started_at' => $startedAt,
                'membership_expires_at' => $startedAt->copy()->addDays(30),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'membership_started_at',
                'membership_expires_at',
            ]);
        });
    }
};
