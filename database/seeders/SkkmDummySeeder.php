<?php

namespace Database\Seeders;

use App\Models\PointRule;
use App\Models\SkkmProgress;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SkkmDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Dosen PA
        $dosen = User::create([
            'name' => 'Dr. Budi Santoso, M.Kom',
            'email' => 'dosen@example.com',
            'role' => 'lecturer',
            'identifier' => '198501012010011001',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Mahasiswa
        $mhs = User::create([
            'name' => 'Ahmad Rendy',
            'email' => 'student@example.com',
            'role' => 'student',
            'identifier' => '220101001',
            'semester' => 3,
            'lecturer_id' => $dosen->id,
            'password' => Hash::make('password'),
        ]);

        // 3. Create Initial Progress
        $progress = SkkmProgress::create([
            'mahasiswa_id' => $mhs->id,
            'jenjang' => 'S1',
            'semester_aktif' => 3,
            'poin_smt_1_2' => 50,
            'total_poin' => 50,
            'status_yudisium' => 'dalam_proses',
        ]);

        // 4. Create Submissions
        
        // Submission 1: Approved (Disetujui)
        $ruleJurnal = PointRule::where('sub_unsur', 'jurnal_ilmiah')->where('tingkat', 'internasional')->first();
        if ($ruleJurnal) {
            SkkmSubmission::create([
                'mahasiswa_id' => $mhs->id,
                'point_rule_id' => $ruleJurnal->id,
                'nama_kegiatan' => 'Publikasi Jurnal Machine Learning Internasional',
                'penyelenggara' => 'IEEE Xplore',
                'tanggal_kegiatan' => '2025-10-10',
                'file_bukti' => 'bukti_skkm/dummy_jurnal.pdf',
                'semester_input' => 2,
                'poin_otomatis' => $ruleJurnal->poin,
                'status_verifikasi' => 'disetujui',
                'verified_by' => $dosen->id,
                'verified_at' => now(),
            ]);
        }

        // Submission 2: Pending (Menunggu)
        $ruleLomba = PointRule::where('sub_unsur', 'lomba_kti')->where('peranan', 'juara_1')->where('tingkat', 'nasional')->first();
        if ($ruleLomba) {
            SkkmSubmission::create([
                'mahasiswa_id' => $mhs->id,
                'point_rule_id' => $ruleLomba->id,
                'nama_kegiatan' => 'Lomba KTI Nasional Green Technology',
                'penyelenggara' => 'Universitas Indonesia',
                'tanggal_kegiatan' => '2026-03-15',
                'file_bukti' => 'bukti_skkm/dummy_lomba.pdf',
                'semester_input' => 3,
                'poin_otomatis' => $ruleLomba->poin,
                'status_verifikasi' => 'pending',
            ]);
        }

        // Submission 3: Rejected (Ditolak)
        $ruleAsdos = PointRule::where('sub_unsur', 'asisten_dosen')->first();
        if ($ruleAsdos) {
            SkkmSubmission::create([
                'mahasiswa_id' => $mhs->id,
                'point_rule_id' => $ruleAsdos->id,
                'nama_kegiatan' => 'Asisten Dosen Pemrograman Web',
                'penyelenggara' => 'Fakultas Ilmu Komputer',
                'tanggal_kegiatan' => '2026-01-20',
                'file_bukti' => 'bukti_skkm/dummy_asdos.pdf',
                'semester_input' => 3,
                'poin_otomatis' => $ruleAsdos->poin,
                'status_verifikasi' => 'ditolak',
                'catatan_dosen' => 'File bukti sertifikat kurang jelas, mohon upload ulang scan aslinya.',
                'verified_by' => $dosen->id,
                'verified_at' => now(),
            ]);
        }
    }
}
