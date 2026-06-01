<?php

namespace Tests\Feature;

use App\Models\Bimbingan;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPhoneNumberIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_store_student_phone_number(): void
    {
        $admin = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'super_admin',
        ]);
        $programStudi = $this->createProgramStudi();
        $lecturer = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'program_studi_id' => $programStudi->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Mahasiswa Contoh',
            'email' => 'mahasiswa@example.com',
            'identifier' => '23010001',
            'phone_number' => '0812-3456-7890',
            'skkm_role' => 'mahasiswa',
            'program_studi_id' => $programStudi->id,
            'jenjang_studi' => 'S1',
            'semester' => 2,
            'lecturer_id' => $lecturer->id,
            'is_active' => 1,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'mahasiswa@example.com',
            'phone_number' => '0812-3456-7890',
        ]);
    }

    public function test_dosen_invitation_whatsapp_link_uses_international_number(): void
    {
        $student = User::factory()->create([
            'name' => 'Mahasiswa Contoh',
            'phone_number' => '0812-3456-7890',
        ]);
        $lecturer = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);
        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $student->id,
            'dosen_id' => $lecturer->id,
            'semester' => 2,
            'tanggal' => '2026-06-05',
            'topik' => 'Konsultasi KRS',
            'tipe_pengajuan' => 'undangan_dosen',
            'status' => 'validated',
        ]);

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $bimbingan->whatsapp_link);
        $this->assertStringContainsString(rawurlencode('Konsultasi KRS'), $bimbingan->whatsapp_link);
    }

    public function test_super_admin_cannot_store_student_with_invalid_whatsapp_number(): void
    {
        $admin = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'super_admin',
        ]);
        $programStudi = $this->createProgramStudi();
        $lecturer = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'program_studi_id' => $programStudi->id,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Mahasiswa Contoh',
                'email' => 'mahasiswa@example.com',
                'identifier' => '23010001',
                'phone_number' => '0812-345',
                'skkm_role' => 'mahasiswa',
                'program_studi_id' => $programStudi->id,
                'jenjang_studi' => 'S1',
                'semester' => 2,
                'lecturer_id' => $lecturer->id,
                'is_active' => 1,
                'password' => 'password',
            ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseMissing('users', ['email' => 'mahasiswa@example.com']);
    }

    private function createProgramStudi(): ProgramStudi
    {
        $fakultas = Fakultas::create([
            'kode' => 'FT',
            'nama' => 'Fakultas Teknik',
        ]);

        return ProgramStudi::create([
            'fakultas_id' => $fakultas->id,
            'kode' => 'IF',
            'nama' => 'Informatika',
            'jenjang' => 'S1',
        ]);
    }
}
