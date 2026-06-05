<?php

namespace Tests\Feature;

use App\Models\Bimbingan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupScheduledBimbinganTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_can_schedule_group_bimbingan_for_all_students(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $student1 = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'lecturer_id' => $dosen->id,
            'identifier' => '23010001',
        ]);

        $student2 = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'lecturer_id' => $dosen->id,
            'identifier' => '23010002',
        ]);

        $response = $this->actingAs($dosen)->post(route('bimbingan.dosen.store'), [
            'filter_type' => 'all',
            'tanggal' => '2026-06-05',
            'topik' => 'Bimbingan Kelompok Awal',
            'catatan' => 'Bawa buku catatan',
        ]);

        $response->assertRedirect(route('bimbingan.dosen.index'));

        // Check both students have bimbingan created with same group key
        $bimbingans = Bimbingan::where('dosen_id', $dosen->id)->get();
        $this->assertCount(2, $bimbingans);
        $this->assertNotEmpty($bimbingans->first()->group_key);
        $this->assertEquals($bimbingans->first()->group_key, $bimbingans->last()->group_key);
    }

    public function test_dosen_can_report_completed_group_bimbingan_updates_all_in_group(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $student1 = User::factory()->create([
            'role' => 'student',
            'lecturer_id' => $dosen->id,
        ]);

        $student2 = User::factory()->create([
            'role' => 'student',
            'lecturer_id' => $dosen->id,
        ]);

        $groupKey = 'grp_test_123';

        $b1 = Bimbingan::create([
            'mahasiswa_id' => $student1->id,
            'dosen_id' => $dosen->id,
            'semester' => 1,
            'tanggal' => '2026-06-05',
            'topik' => 'Group Topic',
            'catatan' => 'Test Notes',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
            'group_key' => $groupKey,
        ]);

        $b2 = Bimbingan::create([
            'mahasiswa_id' => $student2->id,
            'dosen_id' => $dosen->id,
            'semester' => 1,
            'tanggal' => '2026-06-05',
            'topik' => 'Group Topic',
            'catatan' => 'Test Notes',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
            'group_key' => $groupKey,
        ]);

        $response = $this->actingAs($dosen)->post(route('bimbingan.dosen.report', $b1->id), [
            'resolution' => 'Diskusi kelompok berjalan lancar.',
        ]);

        $response->assertRedirect(route('bimbingan.dosen.index'));

        // Assert both updated
        $this->assertDatabaseHas('bimbingans', [
            'id' => $b1->id,
            'status' => 'completed',
            'resolution' => 'Diskusi kelompok berjalan lancar.',
        ]);

        $this->assertDatabaseHas('bimbingans', [
            'id' => $b2->id,
            'status' => 'completed',
            'resolution' => 'Diskusi kelompok berjalan lancar.',
        ]);
    }

    public function test_dosen_can_delete_group_bimbingan_deletes_all_in_group(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $student1 = User::factory()->create(['role' => 'student']);
        $student2 = User::factory()->create(['role' => 'student']);

        $groupKey = 'grp_delete_test';

        $b1 = Bimbingan::create([
            'mahasiswa_id' => $student1->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Group Topic',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
            'group_key' => $groupKey,
        ]);

        $b2 = Bimbingan::create([
            'mahasiswa_id' => $student2->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Group Topic',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
            'group_key' => $groupKey,
        ]);

        $response = $this->actingAs($dosen)->delete(route('bimbingan.dosen.destroy', $b1->id));

        $response->assertRedirect(route('bimbingan.dosen.index'));

        // Assert both deleted
        $this->assertDatabaseMissing('bimbingans', ['id' => $b1->id]);
        $this->assertDatabaseMissing('bimbingans', ['id' => $b2->id]);
    }

    public function test_dosen_can_delete_individual_bimbingan(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $student = User::factory()->create(['role' => 'student']);

        $b = Bimbingan::create([
            'mahasiswa_id' => $student->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Individual Topic',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
        ]);

        $response = $this->actingAs($dosen)->delete(route('bimbingan.dosen.destroy', $b->id));

        $response->assertRedirect(route('bimbingan.dosen.index'));

        // Assert record is deleted
        $this->assertDatabaseMissing('bimbingans', ['id' => $b->id]);
    }

    public function test_bimbingan_dosen_whatsapp_link_generation(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'phone_number' => '0899-8888-7777',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Budi Santoso',
            'identifier' => '22010101',
        ]);

        $b = Bimbingan::create([
            'mahasiswa_id' => $student->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Pengajuan Skripsi',
            'tipe_pengajuan' => 'mandiri_mahasiswa',
            'status' => 'pending',
            'semester' => 4,
        ]);

        $this->assertNotNull($b->dosen_whatsapp_link);
        $this->assertStringStartsWith('https://wa.me/6289988887777?text=', $b->dosen_whatsapp_link);
        $this->assertStringContainsString(rawurlencode('Budi Santoso'), $b->dosen_whatsapp_link);
        $this->assertStringContainsString(rawurlencode('Pengajuan Skripsi'), $b->dosen_whatsapp_link);
    }

    public function test_mahasiswa_submission_flashes_pending_validation_message_and_whatsapp_link(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'name' => 'Dr. Andi Wijaya',
            'phone_number' => '0812-3456-7890',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'name' => 'Siti Nurhaliza',
            'identifier' => '23010001',
            'semester' => 4,
            'lecturer_id' => $dosen->id,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('bimbingan.mahasiswa.store'), [
            'tanggal' => '2026-06-05',
            'topik' => 'Konsultasi KRS',
        ]);

        $response->assertRedirect(route('bimbingan.mahasiswa.index'));
        $response->assertSessionHas('success', 'Pengajuan bimbingan berhasil dikirim. Saat ini pengajuan Anda menunggu validasi dosen PA.');
        $response->assertSessionHas('submitted_bimbingan_topik', 'Konsultasi KRS');
        $response->assertSessionHas('submitted_bimbingan_dosen_name', 'Dr. Andi Wijaya');
        $response->assertSessionHas('submitted_bimbingan_tanggal', '05 Jun 2026');
        $response->assertSessionHas('submitted_bimbingan_whatsapp_link', function (?string $link) {
            return $link !== null
                && str_starts_with($link, 'https://wa.me/6281234567890?text=')
                && str_contains($link, rawurlencode('Siti Nurhaliza'))
                && str_contains($link, rawurlencode('Konsultasi KRS'));
        });

        $this->assertDatabaseHas('bimbingans', [
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosen->id,
            'semester' => 4,
            'topik' => 'Konsultasi KRS',
            'tipe_pengajuan' => 'mandiri_mahasiswa',
            'status' => 'pending',
        ]);
    }

    public function test_mahasiswa_page_renders_submitted_whatsapp_cta(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'name' => 'Dr. Andi Wijaya',
            'phone_number' => '0812-3456-7890',
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'semester' => 4,
            'lecturer_id' => $dosen->id,
        ]);

        $response = $this->actingAs($mahasiswa)
            ->withSession([
                'submitted_bimbingan_topik' => 'Konsultasi KRS',
                'submitted_bimbingan_tanggal' => '05 Jun 2026',
                'submitted_bimbingan_dosen_name' => 'Dr. Andi Wijaya',
                'submitted_bimbingan_whatsapp_link' => 'https://wa.me/6281234567890?text=test',
            ])
            ->get(route('bimbingan.mahasiswa.index'));

        $response->assertOk();
        $response->assertSee('Menunggu Validasi Dosen PA');
        $response->assertSee('Hubungi Dosen PA via WhatsApp');
        $response->assertSee('Konsultasi KRS');
    }
}
