<?php

namespace Tests\Feature;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\SkkmProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function setupData()
    {
        $fakultas = Fakultas::create([
            'kode' => 'FT',
            'nama' => 'Fakultas Teknik',
        ]);

        $programStudi = ProgramStudi::create([
            'fakultas_id' => $fakultas->id,
            'kode' => 'IF',
            'nama' => 'Informatika',
            'jenjang' => 'S1',
        ]);

        $lecturer = User::create([
            'name' => 'Dr. Ahmad, M.Kom.',
            'email' => 'ahmad.dosen@example.com',
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'identifier' => '19870001',
            'is_active' => true,
            'password' => bcrypt('password'),
        ]);

        return compact('fakultas', 'programStudi', 'lecturer');
    }

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_registration_validation_fails_for_empty_fields(): void
    {
        $data = $this->setupData();

        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])
            ->post('/register', []);

        $response->assertSessionHasErrors([
            'name', 'identifier', 'phone_number', 'semester', 'lecturer_id', 'email', 'password'
        ]);
    }

    public function test_registration_validation_fails_for_duplicate_nim(): void
    {
        $data = $this->setupData();
        
        User::create([
            'name' => 'Existing Student',
            'email' => 'existing@example.com',
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'identifier' => '23010001',
            'password' => bcrypt('password'),
        ]);

        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'New Student',
            'identifier' => '23010001',
            'phone_number' => '081234567890',
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 3,
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('identifier');
    }

    public function test_registration_validation_fails_for_non_numeric_semester(): void
    {
        $data = $this->setupData();

        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'Student Name',
            'identifier' => '23010002',
            'phone_number' => '081234567890',
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 'abc',
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('semester');
    }

    public function test_registration_validation_fails_for_invalid_phone_number(): void
    {
        $data = $this->setupData();

        // Test short phone number (9 digits)
        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'Student Name',
            'identifier' => '23010002',
            'phone_number' => '081234567', // 9 digits
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 3,
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors('phone_number');

        // Test non-numeric phone number
        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'Student Name',
            'identifier' => '23010002',
            'phone_number' => '0812-3456-7890', // has dashes
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 3,
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors('phone_number');

        // Test long phone number (16 digits)
        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'Student Name',
            'identifier' => '23010002',
            'phone_number' => '08123456789012345', // 17 digits
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 3,
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors('phone_number');
    }

    public function test_registration_succeeds_and_creates_user_with_skkm_progress(): void
    {
        $data = $this->setupData();

        $response = $this->withSession(['registration_prodi_id' => $data['programStudi']->id])->post('/register', [
            'name' => 'Budi Santoso',
            'identifier' => '220101010',
            'phone_number' => '081234567890',
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 5,
            'lecturer_id' => $data['lecturer']->id,
            'email' => 'budi.santoso@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Pendaftaran berhasil, silahkan masuk');

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'identifier' => '220101010',
            'phone_number' => '081234567890',
            'email' => 'budi.santoso@example.com',
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'program_studi_id' => $data['programStudi']->id,
            'semester' => 5,
            'jenjang_studi' => 'S1',
            'lecturer_id' => $data['lecturer']->id,
            'is_active' => true,
        ]);

        $user = User::where('identifier', '220101010')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('skkm_progress', [
            'mahasiswa_id' => $user->id,
            'jenjang' => 'S1',
            'semester_aktif' => 5,
        ]);
    }
}
