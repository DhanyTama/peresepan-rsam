<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $namaObatList = [
            'Paracetamol',
            'Ibuprofen',
            'Amoxicillin',
            'Cefixime',
            'Vitamin C',
            'Antalgin',
            'CTM',
            'Lansoprazole',
            'Omeprazole',
            'Metformin',
            'Amlodipine',
            'Simvastatin',
            'Atorvastatin',
            'Cetirizine',
            'Albendazole',
            'Dexamethasone',
            'Prednisone',
            'Salbutamol',
            'Mefenamic Acid',
            'Cough Syrup',
            'Magnesium Hydroxide',
            'ORS Sachet',
            'Hydrocortisone Cream',
            'Betadine',
            'Alcohol Swab',
            'Chloramphenicol',
            'Paratusin',
            'Mucosolvan',
            'Neuralgin',
            'Bodrex',
            'Panadol',
            'Antibiotic Ointment',
            'Gastric Medicine',
            'Naproxen',
            'Ranitidine',
            'Sucralfate',
            'Antasida',
            'Strip Bandage',
            'Antiseptic Liquid'
        ];

        for ($i = 1; $i <= 100; $i++) {
            Obat::create([
                'kode_obat' => 'OBT-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_obat' => $faker->randomElement($namaObatList) . ' ' . $faker->randomDigitNotNull(),
                'stok' => $faker->numberBetween(10, 500),
                'harga_jual' => $faker->numberBetween(1000, 500000),
            ]);
        }
    }
}
