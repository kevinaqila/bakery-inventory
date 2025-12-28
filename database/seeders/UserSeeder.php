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
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bakery.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Kasir User
        User::create([
            'name' => 'Kasir User',
            'email' => 'kasir@bakery.test',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        // Create Karyawan User
        User::create([
            'name' => 'Karyawan User',
            'email' => 'karyawan@bakery.test',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);
    }
}
