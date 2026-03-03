<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        Admin::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@pelaporan.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 123, Jakarta',
            'is_active' => true,
        ]);

        // Admin Biasa
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@pelaporan.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567891',
            'address' => 'Jl. Pendidikan No. 124, Jakarta',
            'is_active' => true,
        ]);
    }
}
