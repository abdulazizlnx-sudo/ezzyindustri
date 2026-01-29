<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manajerial');

        $karyawan = User::create([
            'name' => 'Karyawan User',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password'),
        ]);
        $karyawan->assignRole('karyawan');
    }
}
