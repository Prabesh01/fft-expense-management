<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager+fft@cote.ws',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create employees
        $employee1 = User::create([
            'name' => 'John Employee',
            'email' => 'john+fft@cote.ws',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $employee2 = User::create([
            'name' => 'Jane Employee',
            'email' => 'jane+fft@cote.ws',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $this->command->info('Sample users created!');
        $this->command->info('Manager: manager+fft@cote.ws.com / password');
        $this->command->info('Employee 1: john+fft@cote.ws.com / password');
        $this->command->info('Employee 2: jane+fft@cote.ws.com / password');
    }
}
