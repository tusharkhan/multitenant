<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'System Administrator',
            'email' => 'admin@demo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street, Admin City',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'House Owner',
            'email' => 'owner@demo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'house_owner',
            'phone' => '+1234567890',
            'address' => '123 Admin Street, Admin City',
            'is_active' => true,
        ]);
    }
}
