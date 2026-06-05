<?php

namespace Tests\Feature;

use App\Models\Bimbingan;
use App\Models\GuidanceLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardBimbinganTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_dashboard_uses_latest_bimbingan_model_and_renders_discussion_summary(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'name' => 'Dr. Andi Wijaya',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'semester' => 4,
            'lecturer_id' => $dosen->id,
        ]);

        GuidanceLog::create([
            'user_id' => $mahasiswa->id,
            'lecturer_id' => $dosen->id,
            'guidance_date' => '2026-06-10',
            'topic' => 'Data lama tidak boleh muncul',
            'status' => 'validated',
        ]);

        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'semester' => 4,
            'topik' => 'Evaluasi progres studi',
            'catatan' => 'Fokus pada mata kuliah inti.',
            'tipe_pengajuan' => 'mandiri_mahasiswa',
            'status' => 'completed',
            'resolution' => 'Mahasiswa menyusun jadwal belajar mingguan dan konsultasi ulang bulan depan.',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Bimbingan Terakhir');
        $response->assertSee('Evaluasi progres studi');
        $response->assertSee('Lihat detail dan ringkasan');
        $response->assertSee('Ringkasan Pembahasan');
        $response->assertSee('Mahasiswa menyusun jadwal belajar mingguan dan konsultasi ulang bulan depan.');
        $response->assertSee('Dr. Andi Wijaya');
        $response->assertDontSee('Data lama tidak boleh muncul');
    }
}
