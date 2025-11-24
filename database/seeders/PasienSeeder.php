<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            Pasien::create([
                'kode_pasien' => 'PSN-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_pasien' => $faker->name(),
                'alamat' => $faker->address(),
                'no_telepon' => $faker->phoneNumber(),
            ]);
        }
    }
}
