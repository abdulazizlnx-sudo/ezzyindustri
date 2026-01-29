<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Cek apakah roles sudah ada
            $manajerialRole = DB::table('roles')->where('name', 'manajerial')->first();
            $karyawanRole = DB::table('roles')->where('name', 'karyawan')->first();

            if (!$manajerialRole || !$karyawanRole) {
                $this->command->info('Roles not found. Please run permissions seeder first.');
                return;
            }

            // Cek apakah user sudah ada
            if (User::where('email', 'manager@example.com')->exists()) {
                $this->command->info('Manager user already exists.');
                return;
            }

            if (User::where('email', 'karyawan@example.com')->exists()) {
                $this->command->info('Karyawan user already exists.');
                return;
            }

            // Create manager user
            $manager = User::create([
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
            ]);
            $manager->assignRole('manajerial');

            // Create karyawan user
            $karyawan = User::create([
                'name' => 'Karyawan User',
                'email' => 'karyawan@example.com',
                'password' => Hash::make('password'),
            ]);
            $karyawan->assignRole('karyawan');

            $this->command->info('Users created successfully.');

        } catch (\Exception $e) {
            $this->command->error('Error creating users: ' . $e->getMessage());
        }
    }
}
