<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@devicechecker.local')],
            [
                'name' => env('ADMIN_NAME', 'Administrateur'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
