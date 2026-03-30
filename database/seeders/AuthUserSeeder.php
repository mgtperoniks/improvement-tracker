<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'adminppic@peroniks.com'],
            [
                'name' => 'Admin PPIC',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'department_name' => 'PPIC',
            ]
        );

        // 2. Manager
        User::updateOrCreate(
            ['email' => 'mr@peroniks.com'],
            [
                'name' => 'MR Manager',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'department_name' => 'Management',
            ]
        );

        // 3. Director
        User::updateOrCreate(
            ['email' => 'direktur@peroniks.com'],
            [
                'name' => 'Direktur Utama',
                'password' => Hash::make('peronijayajaya123'),
                'role' => 'manager', // User specified 'role: manager' in the requirement for Director
                'department_name' => 'Board',
            ]
        );
    }
}
