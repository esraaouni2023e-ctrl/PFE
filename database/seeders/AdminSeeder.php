<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user or update existing one
        // Admin is a counselor with is_admin = true
        User::updateOrCreate(
            ['email' => 'admin@capavenir.test'],
            [
                'name' => 'Admin CapAvenir',
                'password' => Hash::make('password'),
                'role' => User::ROLE_COUNSELOR,
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created!');
        $this->command->info('Email: admin@capavenir.test');
        $this->command->info('Password: password');
    }
}
