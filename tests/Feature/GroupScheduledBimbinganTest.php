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

        // Assert both status set to canceled
        $this->assertDatabaseHas('bimbingans', ['id' => $b1->id, 'status' => 'canceled']);
        $this->assertDatabaseHas('bimbingans', ['id' => $b2->id, 'status' => 'canceled']);
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

        // Assert record status is set to canceled
        $this->assertDatabaseHas('bimbingans', ['id' => $b->id, 'status' => 'canceled']);
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

    public function test_whatsapp_cancel_link_generation(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'phone_number' => '081234567890',
        ]);

        $b = Bimbingan::create([
            'mahasiswa_id' => $student->id,
            'dosen_id' => User::factory()->create(['role' => 'lecturer'])->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Test Topik',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
        ]);

        $this->assertNotNull($b->whatsapp_cancel_link);
        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $b->whatsapp_cancel_link);
        $this->assertStringContainsString(rawurlencode('Bimbingan dibatalkan. Silakan menghubungi dosen pembimbing untuk penjadwalan ulang. Terima kasih.'), $b->whatsapp_cancel_link);
    }

    public function test_dosen_validation_redirects_with_whatsapp_link(): void
    {
        $dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'phone_number' => '081234567899',
            'name' => 'John Doe',
        ]);

        $b = Bimbingan::create([
            'mahasiswa_id' => $student->id,
            'dosen_id' => $dosen->id,
            'tanggal' => '2026-06-05',
            'topik' => 'Test Topic',
            'tipe_pengajuan' => 'mandiri_mahasiswa',
            'status' => 'pending',
            'semester' => 4,
        ]);

        $response = $this->actingAs($dosen)->post(route('bimbingan.dosen.update', $b->id), [
            'status' => 'validated',
            'catatan' => 'Silakan hadir tepat waktu.',
        ]);

        $response->assertRedirect(route('bimbingan.dosen.index'));
        $response->assertSessionHas('validation_whatsapp_link');
        $response->assertSessionHas('validation_student_name', 'John Doe');
        $response->assertSessionHas('validation_status', 'Disetujui');

        $link = session('validation_whatsapp_link');
        $this->assertNotNull($link);
        $this->assertStringStartsWith('https://wa.me/6281234567899?text=', $link);
        $this->assertStringContainsString(rawurlencode('John Doe'), $link);
        $this->assertStringContainsString(rawurlencode('DISETUJUI'), $link);
        $this->assertStringContainsString(rawurlencode('Silakan hadir tepat waktu.'), $link);
    }
}
