<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\SkkmProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakultasSainsTeknologiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $password = Hash::make('password');

        // 1. Create Faculty
        $fakultas = Fakultas::updateOrCreate(
            ['kode' => 'FST'],
            [
                'nama' => 'Fakultas Sains dan Teknologi',
                'is_active' => true,
            ]
        );

        // 2. Create Study Programs
        $prodiInformatika = ProgramStudi::updateOrCreate(
            ['kode' => 'IF', 'fakultas_id' => $fakultas->id],
            [
                'nama' => 'Informatika',
                'jenjang' => 'S1',
                'registration_code' => 'IF-REG',
                'is_active' => true,
            ]
        );

        $prodiSistemInformasi = ProgramStudi::updateOrCreate(
            ['kode' => 'SI', 'fakultas_id' => $fakultas->id],
            [
                'nama' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'registration_code' => 'SI-REG',
                'is_active' => true,
            ]
        );

        $prodiTeknikRobotika = ProgramStudi::updateOrCreate(
            ['kode' => 'TR', 'fakultas_id' => $fakultas->id],
            [
                'nama' => 'Teknik Robotika',
                'jenjang' => 'S1',
                'registration_code' => 'TR-REG',
                'is_active' => true,
            ]
        );

        // Define study programs to iterate over
        $prodis = [
            [
                'model' => $prodiInformatika,
                'email_prefix' => 'inf',
                'dosen_name' => 'Dr. Hermawan, S.Kom., M.T.',
                'dosen_email' => 'dosen.informatika@example.com',
                'dosen_nidn' => '0412038501',
                'nim_prefix' => '220101',
            ],
            [
                'model' => $prodiSistemInformasi,
                'email_prefix' => 'si',
                'dosen_name' => 'Dr. Rina Wijayanti, S.Kom., M.M.',
                'dosen_email' => 'dosen.si@example.com',
                'dosen_nidn' => '0415068802',
                'nim_prefix' => '220202',
            ],
            [
                'model' => $prodiTeknikRobotika,
                'email_prefix' => 'tr',
                'dosen_name' => 'Dr. Eng. Supriadi, S.T., M.Eng.',
                'dosen_email' => 'dosen.robotika@example.com',
                'dosen_nidn' => '0420098203',
                'nim_prefix' => '220303',
            ]
        ];

        foreach ($prodis as $prodiData) {
            $prodi = $prodiData['model'];

            // Create Dosen PA
            $dosen = User::updateOrCreate(
                ['email' => $prodiData['dosen_email']],
                [
                    'name' => $prodiData['dosen_name'],
                    'role' => 'lecturer',
                    'skkm_role' => 'dosen_pa',
                    'phone_number' => '0812' . $faker->numerify('########'),
                    'identifier' => $prodiData['dosen_nidn'],
                    'program_studi_id' => $prodi->id,
                    'jenjang_studi' => 'S1',
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'password' => $password,
                ]
            );

            // Create 5 Students for each study program
            for ($i = 1; $i <= 5; $i++) {
                $email = 'mhs.' . $prodiData['email_prefix'] . $i . '@example.com';
                $nim = $prodiData['nim_prefix'] . str_pad($i, 3, '0', STR_PAD_LEFT);
                $semester = $faker->numberBetween(1, 8);

                $student = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $faker->name,
                        'role' => 'student',
                        'skkm_role' => 'mahasiswa',
                        'phone_number' => '08' . $faker->numerify('##########'),
                        'identifier' => $nim,
                        'program_studi_id' => $prodi->id,
                        'jenjang_studi' => 'S1',
                        'semester' => $semester,
                        'lecturer_id' => $dosen->id,
                        'is_active' => true,
                        'email_verified_at' => now(),
                        'password' => $password,
                    ]
                );

                // Create or update SKKM Progress
                SkkmProgress::updateOrCreate(
                    ['mahasiswa_id' => $student->id],
                    [
                        'jenjang' => 'S1',
                        'semester_aktif' => $semester,
                        'poin_smt_1_2' => $faker->numberBetween(0, 40),
                        'poin_smt_3_4' => $semester >= 3 ? $faker->numberBetween(0, 30) : 0,
                        'poin_smt_5_6' => $semester >= 5 ? $faker->numberBetween(0, 20) : 0,
                        'poin_smt_7_8' => $semester >= 7 ? $faker->numberBetween(0, 10) : 0,
                        'total_poin' => 0, // Will calculate below
                        'status_yudisium' => 'dalam_proses',
                    ]
                );

                // Calculate total points
                $progress = SkkmProgress::where('mahasiswa_id', $student->id)->first();
                if ($progress) {
                    $total = $progress->poin_smt_1_2 + $progress->poin_smt_3_4 + $progress->poin_smt_5_6 + $progress->poin_smt_7_8;
                    $progress->update(['total_poin' => $total]);
                }
            }
        }
    }
}
