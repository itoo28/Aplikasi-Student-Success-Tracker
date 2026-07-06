<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    {
        if (Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_can_register(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $fakultas = \App\Models\Fakultas::create([
            'kode' => 'FT',
            'nama' => 'Fakultas Teknik',
        ]);

        $programStudi = \App\Models\ProgramStudi::create([
            'fakultas_id' => $fakultas->id,
            'kode' => 'IF',
            'nama' => 'Informatika',
            'jenjang' => 'S1',
        ]);

        $lecturer = \App\Models\User::create([
            'name' => 'Dr. Ahmad, M.Kom.',
            'email' => 'ahmad.dosen@example.com',
            'role' => 'lecturer',
            'skkm_role' => 'dosen_pa',
            'identifier' => '19870001',
            'is_active' => true,
            'password' => bcrypt('password'),
        ]);

        $response = $this->withSession(['registration_prodi_id' => $programStudi->id])->post('/register', [
            'name' => 'Test User',
            'identifier' => '220101015',
            'phone_number' => '081234567890',
            'program_studi_id' => $programStudi->id,
            'semester' => 5,
            'lecturer_id' => $lecturer->id,
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertRedirect(route('login'));
    }
}
