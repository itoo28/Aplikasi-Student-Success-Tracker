<?php

namespace Tests\Feature;

use App\Models\PointRule;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PointRuleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_kemahasiswaan_can_access_point_rule_management(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'super_admin',
        ]);
        $kemahasiswaan = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'kemahasiswaan',
        ]);

        $this->actingAs($superAdmin)
            ->get(route('skkm.point-rules.index'))
            ->assertOk()
            ->assertSee('Manajemen Poin SKKM');

        $this->actingAs($kemahasiswaan)
            ->get(route('skkm.point-rules.index'))
            ->assertOk()
            ->assertSee('Manajemen Poin SKKM');
    }

    public function test_active_point_rule_created_by_kemahasiswaan_is_available_on_student_form(): void
    {
        $kemahasiswaan = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'kemahasiswaan',
        ]);
        $student = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'semester' => 4,
        ]);

        $this->actingAs($kemahasiswaan)
            ->post(route('skkm.point-rules.store'), $this->pointRulePayload())
            ->assertRedirect(route('skkm.point-rules.index'));

        $this->assertDatabaseHas('point_rules', [
            'jenis_item' => 'Seminar Nasional',
            'poin' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($student)
            ->get(route('skkm.create'))
            ->assertOk()
            ->assertSee('Seminar Nasional');
    }

    public function test_student_cannot_access_point_rule_management(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
        ]);

        $this->actingAs($student)
            ->get(route('skkm.point-rules.index'))
            ->assertForbidden();
    }

    public function test_point_rule_used_by_submission_cannot_be_deleted(): void
    {
        $kemahasiswaan = User::factory()->create([
            'role' => 'lecturer',
            'skkm_role' => 'kemahasiswaan',
        ]);
        $student = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
        ]);
        $pointRule = PointRule::create($this->pointRulePayload());

        SkkmSubmission::create([
            'mahasiswa_id' => $student->id,
            'point_rule_id' => $pointRule->id,
            'nama_kegiatan' => 'Seminar Pengembangan Diri',
            'penyelenggara' => 'Universitas',
            'tanggal_kegiatan' => '2026-05-20',
            'file_bukti' => 'bukti_skkm/seminar.pdf',
            'semester_input' => 4,
            'poin_otomatis' => $pointRule->poin,
            'status_verifikasi' => 'pending',
        ]);

        $this->actingAs($kemahasiswaan)
            ->from(route('skkm.point-rules.index'))
            ->delete(route('skkm.point-rules.destroy', $pointRule))
            ->assertRedirect(route('skkm.point-rules.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('point_rules', ['id' => $pointRule->id]);
        $this->assertDatabaseHas('skkm_submissions', ['point_rule_id' => $pointRule->id]);
    }

    public function test_student_cannot_submit_inactive_point_rule(): void
    {
        Storage::fake('public');

        $student = User::factory()->create([
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
        ]);
        $pointRule = PointRule::create([
            ...$this->pointRulePayload(),
            'is_active' => false,
        ]);

        $this->actingAs($student)
            ->post(route('skkm.store'), [
                'point_rule_id' => $pointRule->id,
                'nama_kegiatan' => 'Seminar Pengembangan Diri',
                'penyelenggara' => 'Universitas',
                'tanggal_kegiatan' => '2026-05-20',
                'file_bukti' => UploadedFile::fake()->create('seminar.pdf', 100, 'application/pdf'),
                'semester_input' => 4,
            ])
            ->assertSessionHasErrors('point_rule_id');

        $this->assertDatabaseMissing('skkm_submissions', ['point_rule_id' => $pointRule->id]);
    }

    /**
     * @return array<string, mixed>
     */
    private function pointRulePayload(): array
    {
        return [
            'unsur' => 'penalaran',
            'sub_unsur' => 'seminar',
            'jenis_item' => 'Seminar Nasional',
            'tingkat' => 'nasional',
            'peranan' => 'peserta',
            'poin' => 10,
            'bukti_fisik_required' => 'Sertifikat dan foto kegiatan',
            'keterangan' => 'Kegiatan seminar tingkat nasional.',
            'is_active' => true,
        ];
    }
}
