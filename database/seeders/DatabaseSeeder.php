<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\GuidanceLog;
use App\Models\PointRule;
use App\Models\ProgramStudi;
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
                'phone_number' => '+628111000000',
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
                'phone_number' => '+628112345678',
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
                'phone_number' => '+628113456789',
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
                'phone_number' => '+628114567890',
                'role' => 'lecturer',
                'skkm_role' => 'kemahasiswaan',
                'identifier' => 'KMH001',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $fakultas = Fakultas::firstOrCreate(
            ['kode' => 'FIK'],
            ['nama' => 'Fakultas Ilmu Komputer', 'is_active' => true]
        );

        $prodiInformatika = ProgramStudi::firstOrCreate(
            ['kode' => 'IF-S1'],
            ['nama' => 'S1 Informatika', 'jenjang' => 'S1', 'fakultas_id' => $fakultas->id, 'is_active' => true]
        );

        $prodiSistemInformasi = ProgramStudi::firstOrCreate(
            ['kode' => 'SI-S1'],
            ['nama' => 'S1 Sistem Informasi', 'jenjang' => 'S1', 'fakultas_id' => $fakultas->id, 'is_active' => true]
        );

        $studentsData = [
            ['name' => 'Andi Pratama', 'email' => 'andi.pratama.2022@example.com', 'phone_number' => '+628119000001', 'identifier' => '220101001', 'semester' => 8, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza.2022@example.com', 'phone_number' => '+628119000002', 'identifier' => '220101002', 'semester' => 8, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Rizki Maulana', 'email' => 'rizki.maulana.2022@example.com', 'phone_number' => '+628119000003', 'identifier' => '220101003', 'semester' => 8, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Dewi Sartika', 'email' => 'dewi.sartika.2022@example.com', 'phone_number' => '+628119000004', 'identifier' => '220101004', 'semester' => 8, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Nadia Fitri', 'email' => 'nadia.fitri.2022@example.com', 'phone_number' => '+628119000005', 'identifier' => '220101005', 'semester' => 8, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Rian Aditya', 'email' => 'rian.aditya.2023@example.com', 'phone_number' => '+628119000006', 'identifier' => '230101006', 'semester' => 6, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Intan Maharani', 'email' => 'intan.maharani.2023@example.com', 'phone_number' => '+628119000007', 'identifier' => '230101007', 'semester' => 6, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Muhammad Fadhil', 'email' => 'muhammad.fadhil.2023@example.com', 'phone_number' => '+628119000008', 'identifier' => '230101008', 'semester' => 6, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Larasati Ayu', 'email' => 'larasati.ayu.2023@example.com', 'phone_number' => '+628119000009', 'identifier' => '230101009', 'semester' => 6, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Bima Prasetya', 'email' => 'bima.prasetya.2023@example.com', 'phone_number' => '+628119000010', 'identifier' => '230101010', 'semester' => 6, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Arya Saputra', 'email' => 'arya.saputra.2024@example.com', 'phone_number' => '+628119000011', 'identifier' => '240101011', 'semester' => 4, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho.2024@example.com', 'phone_number' => '+628119000012', 'identifier' => '240101012', 'semester' => 4, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Melati Sari', 'email' => 'melati.sari.2024@example.com', 'phone_number' => '+628119000013', 'identifier' => '240101013', 'semester' => 4, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Arifin Hidayat', 'email' => 'arifin.hidayat.2024@example.com', 'phone_number' => '+628119000014', 'identifier' => '240101014', 'semester' => 4, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Citra Lestari', 'email' => 'citra.lestari.2024@example.com', 'phone_number' => '+628119000015', 'identifier' => '240101015', 'semester' => 4, 'program_studi_id' => $prodiInformatika->id],
            ['name' => 'Ikhsan Aprilia', 'email' => 'ikhsan.aprilia.2025@example.com', 'phone_number' => '+628119000016', 'identifier' => '250101016', 'semester' => 2, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Rofiq Hidayat', 'email' => 'rofiq.hidayat.2025@example.com', 'phone_number' => '+628119000017', 'identifier' => '250101017', 'semester' => 2, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Yulia Safitri', 'email' => 'yulia.safitri.2025@example.com', 'phone_number' => '+628119000018', 'identifier' => '250101018', 'semester' => 2, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Gilang Pratama', 'email' => 'gilang.pratama.2025@example.com', 'phone_number' => '+628119000019', 'identifier' => '250101019', 'semester' => 2, 'program_studi_id' => $prodiSistemInformasi->id],
            ['name' => 'Nadia Rahma', 'email' => 'nadia.rahma.2025@example.com', 'phone_number' => '+628119000020', 'identifier' => '250101020', 'semester' => 2, 'program_studi_id' => $prodiSistemInformasi->id],
        ];

        $firstStudent = null;
        foreach ($studentsData as $index => $studentData) {
            $studentRecord = User::updateOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'phone_number' => $studentData['phone_number'],
                    'role' => 'student',
                    'skkm_role' => 'mahasiswa',
                    'identifier' => $studentData['identifier'],
                    'semester' => $studentData['semester'],
                    'jenjang_studi' => 'S1',
                    'lecturer_id' => $dosenPa->id,
                    'program_studi_id' => $studentData['program_studi_id'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]
            );

            if ($index === 0) {
                $firstStudent = $studentRecord;
            }
        }

        $rule = PointRule::query()->first();

        if ($rule) {
            SkkmSubmission::firstOrCreate(
                [
                    'mahasiswa_id' => $firstStudent->id,
                    'nama_kegiatan' => 'Seminar Nasional Teknologi Pendidikan',
                ],
                [
                    'point_rule_id' => $rule->id,
                    'penyelenggara' => 'Universitas Harapan Bangsa',
                    'tanggal_kegiatan' => now()->subDays(10)->toDateString(),
                    'file_bukti' => 'bukti_skkm/dummy_seminar.pdf',
                    'semester_input' => 8,
                    'poin_otomatis' => $rule->poin,
                    'status_verifikasi' => 'pending',
                    'is_progress_counted' => false,
                ]
            );
        }

        SkkmProgress::updateOrCreate(
            ['mahasiswa_id' => $firstStudent->id],
            [
                'jenjang' => 'S1',
                'semester_aktif' => 8,
                'poin_smt_1_2' => 20,
                'poin_smt_3_4' => 15,
                'poin_smt_5_6' => 0,
                'poin_smt_7_8' => 0,
                'total_poin' => 35,
                'status_yudisium' => 'dalam_proses',
            ]
        );

        GuidanceLog::firstOrCreate(
            [
                'user_id' => $firstStudent->id,
                'guidance_date' => now()->addDays(2)->toDateString(),
                'topic' => 'Permohonan bimbingan KRS',
            ],
            [
                'lecturer_id' => $dosenPa->id,
                'semester' => 8,
                'document_path' => null,
                'status' => 'pending',
                'notes' => '-',
                'resolution' => null,
                'is_completed' => false,
            ]
        );

        GuidanceLog::firstOrCreate(
            [
                'user_id' => $firstStudent->id,
                'guidance_date' => now()->subDays(3)->toDateString(),
                'topic' => 'Konsultasi proposal skripsi',
            ],
            [
                'lecturer_id' => $dosenPa->id,
                'semester' => 8,
                'document_path' => null,
                'status' => 'validated',
                'notes' => 'Jadwal disetujui, hadirkan draft proposal.',
                'resolution' => 'Mahasiswa diminta memperbaiki metodologi dan membuat jadwal revisi.',
                'activity_photo_path' => null,
                'is_completed' => true,
            ]
        );
    }
}
