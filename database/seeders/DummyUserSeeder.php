<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $password = \Illuminate\Support\Facades\Hash::make('password');

        // 1 Dosen PA Informatika
        $dosenInf = \App\Models\User::create([
            'name' => 'Dosen PA Informatika, M.Kom.',
            'email' => 'dosen.inf@example.com',
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'identifier' => 'NIDNINF01',
            'program_studi_id' => 1, // Informatika
            'jenjang_studi' => 'S1',
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => $password,
        ]);

        // 2 Dosen PA Sistem Informasi
        $dosenSi1 = \App\Models\User::create([
            'name' => 'Dosen PA SI 1, M.Kom.',
            'email' => 'dosen.si1@example.com',
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'identifier' => 'NIDNSI01',
            'program_studi_id' => 2, // Sistem Informasi
            'jenjang_studi' => 'S1',
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => $password,
        ]);

        $dosenSi2 = \App\Models\User::create([
            'name' => 'Dosen PA SI 2, M.Kom.',
            'email' => 'dosen.si2@example.com',
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'identifier' => 'NIDNSI02',
            'program_studi_id' => 2, // Sistem Informasi
            'jenjang_studi' => 'S1',
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => $password,
        ]);

        // 20 Mahasiswa Informatika
        for ($i = 1; $i <= 20; $i++) {
            \App\Models\User::create([
                'name' => $faker->name,
                'email' => 'mhs.inf' . $i . '@example.com',
                'role' => 'student',
                'skkm_role' => 'mahasiswa',
                'identifier' => '10010' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'program_studi_id' => 1,
                'jenjang_studi' => 'S1',
                'semester' => $faker->numberBetween(1, 8),
                'lecturer_id' => $dosenInf->id,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $password,
            ]);
        }

        // 20 Mahasiswa Sistem Informasi
        for ($i = 1; $i <= 20; $i++) {
            \App\Models\User::create([
                'name' => $faker->name,
                'email' => 'mhs.si' . $i . '@example.com',
                'role' => 'student',
                'skkm_role' => 'mahasiswa',
                'identifier' => '20010' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'program_studi_id' => 2,
                'jenjang_studi' => 'S1',
                'semester' => $faker->numberBetween(1, 8),
                'lecturer_id' => $i <= 10 ? $dosenSi1->id : $dosenSi2->id,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => $password,
            ]);
        }
    }
}
