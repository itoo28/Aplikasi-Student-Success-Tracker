<?php

namespace Database\Seeders;

use App\Models\PointRule;
use App\Models\SkkmProgress;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PointRuleSeeder::class);

        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin Sistem',
                'role' => 'lecturer',
                'skkm_role' => 'super_admin',
                'identifier' => 'SUP001',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $dosenPa = User::updateOrCreate(
            ['email' => 'ahmad.dosen@example.com'],
            [
                'name' => 'Dr. Ahmad, S.Kom., M.Kom.',
                'role' => 'lecturer',
                'skkm_role' => 'dosen_pa',
                'identifier' => '19870001',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kaprodi@example.com'],
            [
                'name' => 'Kaprodi Teknik Informatika',
                'role' => 'lecturer',
                'skkm_role' => 'kaprodi',
                'identifier' => 'KAP001',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kemahasiswaan@example.com'],
            [
                'name' => 'Admin Kemahasiswaan',
                'role' => 'lecturer',
                'skkm_role' => 'kemahasiswaan',
                'identifier' => 'KMH001',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $student = User::updateOrCreate(
            ['email' => 'budi.mahasiswa@example.com'],
            [
                'name' => 'Budi Santoso',
                'role' => 'student',
                'skkm_role' => 'mahasiswa',
                'identifier' => '220101010',
                'semester' => 5,
                'jenjang_studi' => 'S1',
                'lecturer_id' => $dosenPa->id,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $rule = PointRule::query()->first();

        if ($rule) {
            SkkmSubmission::firstOrCreate(
                [
                    'mahasiswa_id' => $student->id,
                    'nama_kegiatan' => 'Seminar Nasional Teknologi Pendidikan',
                ],
                [
                    'point_rule_id' => $rule->id,
                    'penyelenggara' => 'Universitas Harapan Bangsa',
                    'tanggal_kegiatan' => now()->subDays(10)->toDateString(),
                    'file_bukti' => 'bukti_skkm/dummy_seminar.pdf',
                    'semester_input' => 5,
                    'poin_otomatis' => $rule->poin,
                    'status_verifikasi' => 'pending',
                    'is_progress_counted' => false,
                ]
            );
        }

        SkkmProgress::updateOrCreate(
            ['mahasiswa_id' => $student->id],
            [
                'jenjang' => 'S1',
                'semester_aktif' => 5,
                'poin_smt_1_2' => 20,
                'poin_smt_3_4' => 15,
                'poin_smt_5_6' => 0,
                'poin_smt_7_8' => 0,
                'total_poin' => 35,
                'status_yudisium' => 'dalam_proses',
            ]
        );
    }
}
