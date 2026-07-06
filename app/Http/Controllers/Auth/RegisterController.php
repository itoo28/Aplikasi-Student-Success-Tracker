<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\SkkmProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (!session()->has('registration_prodi_id')) {
            return view('auth.register', [
                'fakultas' => collect(),
                'programStudis' => collect(),
                'lecturers' => collect(),
            ]);
        }

        $prodiId = session('registration_prodi_id');
        $verifiedProdi = ProgramStudi::with('fakultas')->where('is_active', true)->findOrFail($prodiId);

        $fakultas = Fakultas::where('is_active', true)->orderBy('nama')->get();
        $programStudis = ProgramStudi::where('is_active', true)->orderBy('nama')->get();
        $lecturers = User::where('skkm_role', 'dosen_pa')->where('is_active', true)->orderBy('name')->get();

        return view('auth.register', compact('fakultas', 'programStudis', 'lecturers', 'verifiedProdi'));
    }

    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'registration_code' => ['required', 'string'],
        ], [
            'registration_code.required' => 'Kode unik pendaftaran wajib diisi.',
        ]);

        $prodi = ProgramStudi::where('registration_code', $validated['registration_code'])
            ->where('is_active', true)
            ->first();

        if (!$prodi) {
            return back()->withErrors(['registration_code' => 'Kode unik pendaftaran tidak valid atau Program Studi sedang tidak aktif.']);
        }

        session(['registration_prodi_id' => $prodi->id]);

        return redirect()->route('register');
    }

    public function resetCode()
    {
        session()->forget('registration_prodi_id');
        return redirect()->route('register');
    }

    public function register(Request $request)
    {
        if (!session()->has('registration_prodi_id')) {
            return redirect()->route('register')->withErrors(['registration_code' => 'Silahkan verifikasi kode pendaftaran terlebih dahulu.']);
        }

        // Force program_studi_id to match session for security
        $request->merge(['program_studi_id' => session('registration_prodi_id')]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identifier' => ['required', 'string', 'max:50', 'unique:users,identifier'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'program_studi_id' => ['required', 'exists:program_studis,id'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'lecturer_id' => ['required', 'exists:users,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama Lengkap wajib diisi.',
            'identifier.required' => 'NIM wajib diisi.',
            'identifier.unique' => 'NIM sudah terdaftar.',
            'phone_number.required' => 'Nomor HP wajib diisi.',
            'phone_number.regex' => 'Nomor HP harus berupa angka dengan panjang 10 sampai 15 digit.',
            'program_studi_id.required' => 'Program Study wajib diisi.',
            'program_studi_id.exists' => 'Program Study tidak valid.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal adalah 1.',
            'semester.max' => 'Semester maksimal adalah 14.',
            'lecturer_id.required' => 'Dosen Pembimbing Akademik wajib diisi.',
            'lecturer_id.exists' => 'Dosen Pembimbing Akademik tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $programStudi = ProgramStudi::findOrFail($validated['program_studi_id']);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => 'student',
            'skkm_role' => 'mahasiswa',
            'identifier' => $validated['identifier'],
            'semester' => $validated['semester'],
            'jenjang_studi' => $programStudi->jenjang,
            'lecturer_id' => $validated['lecturer_id'],
            'program_studi_id' => $validated['program_studi_id'],
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        // Create initial SKKM Progress entry
        SkkmProgress::create([
            'mahasiswa_id' => $user->id,
            'jenjang' => $user->jenjang_studi,
            'semester_aktif' => $user->semester,
        ]);

        session()->forget('registration_prodi_id');

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil, silahkan masuk');
    }
}
