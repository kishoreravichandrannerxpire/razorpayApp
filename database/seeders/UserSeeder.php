<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id' => 1, 'name' => 'Kishore', 'email' => 'kishore@gmail.com', 'phone' => '9876543210', 'password' => Hash::make('123456')],
            ['id' => 2, 'name' => 'Arun', 'email' => 'arun@gmail.com', 'phone' => '9876543211', 'password' => Hash::make('123456')],
            ['id' => 3, 'name' => 'Priya', 'email' => 'priya@gmail.com', 'phone' => '9876543212', 'password' => Hash::make('123456')],
            ['id' => 4, 'name' => 'Rahul', 'email' => 'rahul@gmail.com', 'phone' => '9876543213', 'password' => Hash::make('123456')],
            ['id' => 5, 'name' => 'Sneha', 'email' => 'sneha@gmail.com', 'phone' => '9876543214', 'password' => Hash::make('123456')],
            ['id' => 6, 'name' => 'Vijay', 'email' => 'vijay@gmail.com', 'phone' => '9876543215', 'password' => Hash::make('123456')],
            ['id' => 7, 'name' => 'Divya', 'email' => 'divya@gmail.com', 'phone' => '9876543216', 'password' => Hash::make('123456')],
            ['id' => 8, 'name' => 'Ajay', 'email' => 'ajay@gmail.com', 'phone' => '9876543217', 'password' => Hash::make('123456')],
            ['id' => 9, 'name' => 'Meena', 'email' => 'meena@gmail.com', 'phone' => '9876543218', 'password' => Hash::make('123456')],
            ['id' => 10, 'name' => 'Ravi', 'email' => 'ravi@gmail.com', 'phone' => '9876543219', 'password' => Hash::make('123456')],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['id' => $user['id']], $user);
        }
    }
}
