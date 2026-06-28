<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────
        // Admin
        // ─────────────────────────────────────────
        User::factory()->admin()->create([
            'full_name' => 'Admin',
            'email'     => 'admin@foodify.com',
            'password'  => bcrypt('password'),
        ]);

        // ─────────────────────────────────────────
        // Clients (10 fake)
        // ─────────────────────────────────────────
        User::factory(10)->create();

        // ─────────────────────────────────────────
        // Client ثابت للـ Testing
        // ─────────────────────────────────────────
        User::factory()->create([
            'full_name' => 'Test Client',
            'email'     => 'client@foodify.com',
            'password'  => bcrypt('password'),
        ]);
    }
}