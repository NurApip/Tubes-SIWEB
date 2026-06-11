<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Khusus Admin (Hanya lewat kode ini)
        User::create([
            'name' => 'Admin FutsalHub',
            'email' => 'adminfutsal@gmail.com',
            'password' => Hash::make('Admin123'), // Password untuk login Admin
            'role' => 1, // Set 1 agar punya akses ke Dashboard Admin
        ]);


    }
}