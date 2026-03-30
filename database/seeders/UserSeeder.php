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
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@kaizen.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Supervisor A',
            'email' => 'spv_a@kaizen.com',
            'password' => Hash::make('password'),
            'role' => 'spv',
            'department_name' => 'Production',
        ]);
    }
}
