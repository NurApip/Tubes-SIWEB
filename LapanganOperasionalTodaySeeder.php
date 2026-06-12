<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LapanganOperasionalTodaySeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            'Pagi' => false,   // is_full = false (available)
            'Siang' => false,
            'Sore' => false,
            'Malam' => false,
        ];

        $lapangans = Lapangan::all(['lapangan_id']);

        foreach ($lapangans as $lapangan) {
            foreach ($slots as $slot => $isFull) {
                DB::table('lapangan_operasional_today')->updateOrInsert(
                    [
                        'lapangan_id' => $lapangan->lapangan_id,
                        'slot' => $slot,
                    ],
                    [
                        'is_full' => $isFull,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
