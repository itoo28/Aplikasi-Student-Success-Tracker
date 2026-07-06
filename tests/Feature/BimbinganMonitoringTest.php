<?php

namespace Tests\Feature;

use App\Models\Bimbingan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BimbinganMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $kaprodi;
    private User $kemahasiswaan;
    private User $student;
    private User $dosen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'super_admin',
        ]);

        $this->kaprodi = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'kaprodi',
        ]);

        $this->kemahasiswaan = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'kemahasiswaan',
        ]);

        $this->dosen = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'lecturer_id' => $this->dosen->id,
            'semester' => 3,
        ]);
    }

    public function test_super_admin_can_access_monitoring_and_detail(): void
    {
        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $this->student->id,
            'dosen_id' => $this->dosen->id,
            'semester' => 3,
            'tanggal' => now(),
            'topik' => 'Penyusunan KRS',
            'status' => 'completed',
            'resolution' => 'KRS disetujui',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan'));
        $response->assertStatus(200);
        $response->assertSee($this->student->name);

        $responseDetail = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan.detail', $this->student->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($this->student->name);
        $responseDetail->assertSee('Penyusunan KRS');
        $responseDetail->assertSee('KRS disetujui');
    }

    public function test_kaprodi_can_access_monitoring_and_detail(): void
    {
        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $this->student->id,
            'dosen_id' => $this->dosen->id,
            'semester' => 3,
            'tanggal' => now(),
            'topik' => 'Penyusunan KRS',
            'status' => 'completed',
            'resolution' => 'KRS disetujui',
        ]);

        $response = $this->actingAs($this->kaprodi)->get(route('bimbingan.rekapitulasi.kaprodi'));
        $response->assertStatus(200);
        $response->assertSee($this->student->name);

        $responseDetail = $this->actingAs($this->kaprodi)->get(route('bimbingan.rekapitulasi.kaprodi.detail', $this->student->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($this->student->name);
        $responseDetail->assertSee('Penyusunan KRS');
    }

    public function test_kemahasiswaan_can_access_monitoring_and_detail(): void
    {
        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $this->student->id,
            'dosen_id' => $this->dosen->id,
            'semester' => 3,
            'tanggal' => now(),
            'topik' => 'Penyusunan KRS',
            'status' => 'completed',
            'resolution' => 'KRS disetujui',
        ]);

        $response = $this->actingAs($this->kemahasiswaan)->get(route('bimbingan.rekapitulasi.kemahasiswaan'));
        $response->assertStatus(200);
        $response->assertSee($this->student->name);

        $responseDetail = $this->actingAs($this->kemahasiswaan)->get(route('bimbingan.rekapitulasi.kemahasiswaan.detail', $this->student->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($this->student->name);
        $responseDetail->assertSee('Penyusunan KRS');
    }

    public function test_student_cannot_access_monitoring_or_details(): void
    {
        $responseIndex = $this->actingAs($this->student)->get(route('bimbingan.rekapitulasi.kaprodi'));
        $responseIndex->assertStatus(403);

        $responseAdminIndex = $this->actingAs($this->student)->get(route('admin.bimbingan'));
        $responseAdminIndex->assertStatus(403);
    }

    public function test_users_can_search_by_name_nim_or_study_program(): void
    {
        $fakultas = \App\Models\Fakultas::create([
            'kode' => 'FT',
            'nama' => 'Fakultas Teknik',
        ]);

        $prodi1 = \App\Models\ProgramStudi::create([
            'fakultas_id' => $fakultas->id,
            'kode' => 'PRODI1',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'S1',
            'is_active' => true,
        ]);
        $prodi2 = \App\Models\ProgramStudi::create([
            'fakultas_id' => $fakultas->id,
            'kode' => 'PRODI2',
            'nama' => 'Sistem Informasi',
            'jenjang' => 'S1',
            'is_active' => true,
        ]);

        $this->student->update([
            'name' => 'Budi Santoso',
            'identifier' => 'NIM11111',
            'program_studi_id' => $prodi1->id,
            'semester' => 3,
        ]);

        // Create another student
        $student2 = User::factory()->create([
            'name' => 'Jane Doe',
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'lecturer_id' => $this->dosen->id,
            'identifier' => 'NIM99999',
            'program_studi_id' => $prodi2->id,
            'semester' => 4,
        ]);

        // Search by name "Jane"
        $response = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan', ['search' => 'Jane']));
        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
        $response->assertDontSee('Budi Santoso');

        // Search by identifier "NIM99999"
        $response2 = $this->actingAs($this->kaprodi)->get(route('bimbingan.rekapitulasi.kaprodi', ['search' => 'NIM99999']));
        $response2->assertStatus(200);
        $response2->assertSee('Jane Doe');
        $response2->assertDontSee('Budi Santoso');

        // Filter by Program Studi only
        $response3 = $this->actingAs($this->kemahasiswaan)->get(route('bimbingan.rekapitulasi.kemahasiswaan', ['program_studi_id' => $prodi2->id]));
        $response3->assertStatus(200);
        $response3->assertSee('Jane Doe');
        $response3->assertDontSee('Budi Santoso');

        // Filter by Semester only
        $response4 = $this->actingAs($this->kemahasiswaan)->get(route('bimbingan.rekapitulasi.kemahasiswaan', ['semester' => 3]));
        $response4->assertStatus(200);
        $response4->assertSee('Budi Santoso');
        $response4->assertDontSee('Jane Doe');

        // Filter by Search, Prodi, and Semester simultaneously
        $response5 = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan', [
            'search' => 'Jane',
            'program_studi_id' => $prodi2->id,
            'semester' => 4
        ]));
        $response5->assertStatus(200);
        $response5->assertSee('Jane Doe');
        $response5->assertDontSee('Budi Santoso');
    }

    public function test_users_can_filter_by_status_bimbingan(): void
    {
        // $this->student has 0 completed bimbingan for semester 3.
        // Create another student who has at least 1 completed bimbingan for their current semester.
        $student2 = User::factory()->create([
            'name' => 'Jane Doe',
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'lecturer_id' => $this->dosen->id,
            'semester' => 4,
        ]);

        Bimbingan::create([
            'mahasiswa_id' => $student2->id,
            'dosen_id' => $this->dosen->id,
            'semester' => 4, // matching current semester
            'tanggal' => now(),
            'topik' => 'Penyusunan KRS',
            'status' => 'completed',
        ]);

        // Filter status_bimbingan = sudah (should show Jane Doe, not Budi Santoso)
        $responseAlready = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan', ['status_bimbingan' => 'sudah']));
        $responseAlready->assertStatus(200);
        $responseAlready->assertSee('Jane Doe');
        $responseAlready->assertDontSee($this->student->name);

        // Filter status_bimbingan = belum (should show Budi Santoso, not Jane Doe)
        $responseNotYet = $this->actingAs($this->superAdmin)->get(route('admin.bimbingan', ['status_bimbingan' => 'belum']));
        $responseNotYet->assertStatus(200);
        $responseNotYet->assertSee($this->student->name);
        $responseNotYet->assertDontSee('Jane Doe');
    }
}
