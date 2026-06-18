<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Khusus Admin (Hanya lewat kode ini)
        User::updateOrCreate(
            ['email' => 'adminfutsal@gmail.com'],
            [
                'name' => 'Admin FutsalHub',
                'password' => 'admin1234',
                'role' => 1,
            ]
        );
    }
}
