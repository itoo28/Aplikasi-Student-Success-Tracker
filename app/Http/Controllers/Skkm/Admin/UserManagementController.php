<?php

namespace App\Http\Controllers\Skkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->query('role');

        $users = User::query()
            ->with(['programStudi.fakultas', 'lecturer'])
            ->when($role, fn ($query) => $query->where('skkm_role', $role))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('skkm.super-admin.users.index', [
            'users' => $users,
            'selectedRole' => $role,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function create(): View
    {
        return view('skkm.super-admin.users.create', [
            'user' => new User(),
            'programStudis' => ProgramStudi::query()->with('fakultas')->orderBy('nama')->get(),
            'lecturers' => User::query()->where('skkm_role', 'dosen_pa')->orderBy('name')->get(),
            'roleOptions' => $this->roleOptions(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();
        $validated['role'] = $validated['skkm_role'] === 'mahasiswa' ? 'student' : 'lecturer';

        if ($validated['skkm_role'] === 'kaprodi') {
            $validated['jenjang_studi'] = ProgramStudi::whereKey($validated['program_studi_id'])->value('jenjang');
        }

        if ($validated['skkm_role'] !== 'mahasiswa') {
            $validated['semester'] = null;
            $validated['lecturer_id'] = null;
            if ($validated['skkm_role'] !== 'kaprodi') {
                $validated['jenjang_studi'] = null;
            }
        }

        if (! in_array($validated['skkm_role'], ['mahasiswa', 'kaprodi'], true)) {
            $validated['program_studi_id'] = null;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('skkm.super-admin.users.create', [
            'user' => $user,
            'programStudis' => ProgramStudi::query()->with('fakultas')->orderBy('nama')->get(),
            'lecturers' => User::query()->where('skkm_role', 'dosen_pa')->orderBy('name')->get(),
            'roleOptions' => $this->roleOptions(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['role'] = $validated['skkm_role'] === 'mahasiswa' ? 'student' : 'lecturer';

        if ($validated['skkm_role'] === 'kaprodi') {
            $validated['jenjang_studi'] = ProgramStudi::whereKey($validated['program_studi_id'])->value('jenjang');
        }

        if ($validated['skkm_role'] !== 'mahasiswa') {
            $validated['semester'] = null;
            $validated['lecturer_id'] = null;
            if ($validated['skkm_role'] !== 'kaprodi') {
                $validated['jenjang_studi'] = null;
            }
        }

        if (! in_array($validated['skkm_role'], ['mahasiswa', 'kaprodi'], true)) {
            $validated['program_studi_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user, Request $request): RedirectResponse
    {
        if ((int) $user->id === (int) $request->user()->id) {
            return redirect()->back()->withErrors(['delete_user' => 'Akun super admin yang sedang aktif tidak bisa dihapus.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function roleOptions(): array
    {
        return [
            'mahasiswa' => 'Mahasiswa',
            'dosen_pa' => 'Dosen PA',
            'kaprodi' => 'Kaprodi',
            'kemahasiswaan' => 'Kemahasiswaan',
            'super_admin' => 'Super Admin',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;

        $passwordRules = $user
            ? ['nullable', 'string', 'min:8']
            : ['required', 'string', 'min:8'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'identifier' => ['nullable', 'string', 'max:50', Rule::unique('users', 'identifier')->ignore($userId)],
            'skkm_role' => ['required', Rule::in(array_keys($this->roleOptions()))],
            'program_studi_id' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->input('skkm_role'), ['mahasiswa', 'kaprodi'], true)),
                'exists:program_studis,id',
            ],
            'jenjang_studi' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('skkm_role') === 'mahasiswa'),
                Rule::in(['S1', 'D4', 'D3']),
            ],
            'semester' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('skkm_role') === 'mahasiswa'),
                'integer',
                'min:1',
                'max:14',
            ],
            'lecturer_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('skkm_role') === 'mahasiswa'),
                'exists:users,id',
            ],
            'is_active' => ['nullable', 'boolean'],
            'password' => $passwordRules,
        ]);
    }
}
