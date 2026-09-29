<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed a single admin user for testing.
     * Email: admin@example.com | Password: password | Role: admin
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kishoreravichandran7@gmail.com'],
            [
                'name'     => 'Kishore',
                'email'    => 'kishoreravichandran7@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => null,
            ]
        );
    }
}
