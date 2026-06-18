<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FotoLapangan;

class FotoLapanganSeeder extends Seeder
{
    public function run(): void
    {
        // Foto buat Lapangan ID 1
        FotoLapangan::create(['lapangan_id' => 1, 'path_foto' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1000']);
        FotoLapangan::create(['lapangan_id' => 1, 'path_foto' => 'https://images.unsplash.com/photo-1529900948632-58674ba193cb?q=80&w=1000']);

        // Foto buat Lapangan ID 2
        FotoLapangan::create(['lapangan_id' => 2, 'path_foto' => 'https://images.unsplash.com/photo-1551958219-acbc608c6377?q=80&w=1000']);
        FotoLapangan::create(['lapangan_id' => 2, 'path_foto' => 'https://images.unsplash.com/photo-1459865264687-595d652de67e?q=80&w=1000']);
    }
}