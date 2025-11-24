<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApotekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Apoteker',
            'email' => 'apoteker@example.com',
            'password' => bcrypt('apotekerpassword'),
            'role' => 'apoteker',
        ]);
    }
}
