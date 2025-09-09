<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'full_name' => 'Vincent Raphael M. Lacaste',
            'school_id' => '22-0000-001',
            'email'     => 'lacaste@udd.com',
            'phone'     => '09111111111',
            'photo'     => null,
            'role'      => 'Admin',
            'password'  => Hash::make('admin123'),
        ]);

        User::create([
            'full_name' => 'Laurence P. Dingle',
            'school_id' => '22-0000-002',
            'email'     => 'dingle@udd.com',
            'phone'     => '09222222222',
            'photo'     => null,
            'role'      => 'Checker',
            'password'  => Hash::make('checker123'),
        ]);
    }
}
