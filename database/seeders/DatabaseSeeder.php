<?php

namespace Database\Seeders;

use App\Models\GuidanceLog;
use App\Models\SkkmPoint;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lecturer = User::query()->create([
            'name' => 'Dr. Ahmad, S.Kom., M.Kom.',
            'email' => 'ahmad.dosen@example.com',
            'role' => 'lecturer',
            'identifier' => '19870001',
            'semester' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $student = User::query()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi.mahasiswa@example.com',
            'role' => 'student',
            'identifier' => '220101010',
            'semester' => 5,
            'lecturer_id' => $lecturer->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $studentTwo = User::query()->create([
            'name' => 'Siti Aminah',
            'email' => 'siti.mahasiswa@example.com',
            'role' => 'student',
            'identifier' => '220101012',
            'semester' => 6,
            'lecturer_id' => $lecturer->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        SkkmPoint::query()->insert([
            [
                'user_id' => $student->id,
                'name' => 'Panitia Seminar Nasional',
                'category' => 'Organisasi',
                'points' => 5,
                'status' => 'approved',
                'approved_by' => $lecturer->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => $student->id,
                'name' => 'Juara 3 Lomba Esai',
                'category' => 'Minat',
                'points' => 10,
                'status' => 'approved',
                'approved_by' => $lecturer->id,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => $student->id,
                'name' => 'Workshop Web Development',
                'category' => 'Penalaran',
                'points' => 2,
                'status' => 'pending',
                'approved_by' => null,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'user_id' => $studentTwo->id,
                'name' => 'Relawan Bencana',
                'category' => 'Pengabdian',
                'points' => 5,
                'status' => 'pending',
                'approved_by' => null,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id' => $studentTwo->id,
                'name' => 'Asisten Laboratorium',
                'category' => 'Penalaran',
                'points' => 3,
                'status' => 'approved',
                'approved_by' => $lecturer->id,
                'created_at' => now()->subDays(40),
                'updated_at' => now()->subDays(40),
            ],
        ]);

        GuidanceLog::query()->insert([
            [
                'user_id' => $student->id,
                'lecturer_id' => $lecturer->id,
                'guidance_date' => now()->subDays(12)->toDateString(),
                'topic' => 'Evaluasi KRS dan Rencana Magang',
                'notes' => 'Perlu peningkatan nilai Algoritma sebelum ambil topik magang.',
                'status' => 'validated',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(11),
            ],
            [
                'user_id' => $student->id,
                'lecturer_id' => $lecturer->id,
                'guidance_date' => now()->subDays(2)->toDateString(),
                'topic' => 'Konsultasi Persiapan Proposal',
                'notes' => 'Mahasiswa diminta melengkapi referensi jurnal terbaru.',
                'status' => 'pending',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $studentTwo->id,
                'lecturer_id' => $lecturer->id,
                'guidance_date' => now()->toDateString(),
                'topic' => 'Monitoring Progress Skripsi',
                'notes' => 'Progress sesuai timeline, lanjut ke bab metodologi.',
                'status' => 'validated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
