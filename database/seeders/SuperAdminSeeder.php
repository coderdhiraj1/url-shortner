<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'superadmin@example.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345'),
                'role' => Role::SUPER_ADMIN,
            ]
        );
    }
}