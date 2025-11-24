<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dokter',
            'email' => 'dokter@example.com',
            'password' => bcrypt('dokterpassword'),
            'role' => 'dokter',
        ]);
    }
}
