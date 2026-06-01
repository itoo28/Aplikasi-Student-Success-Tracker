<?php

namespace App\Http\Controllers\Skkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $roleOptions = $this->roleOptions();
        $role = $request->query('role');
        $role = is_string($role) && array_key_exists($role, $roleOptions) ? $role : null;

        $search = trim((string) $request->query('search', ''));
        $search = $search !== '' ? $search : null;

        $programStudiId = $request->query('program_studi_id');
        $programStudiId = is_numeric($programStudiId) ? (int) $programStudiId : null;

        $semester = $request->query('semester');
        $semester = is_numeric($semester) ? (int) $semester : null;

        if ($semester !== null && ($semester < 1 || $semester > 14)) {
            $semester = null;
        }

        if ($role !== 'mahasiswa') {
            $programStudiId = null;
            $semester = null;
        }

        $sortOptions = $this->sortOptions();
        $sort = (string) $request->query('sort', 'name_asc');
        $sort = array_key_exists($sort, $sortOptions) ? $sort : 'name_asc';

        $perPageOptions = [15, 30, 50, 100];
        $perPage = (int) $request->query('per_page', 15);
        $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 15;

        $usersQuery = User::query()
            ->with(['programStudi.fakultas', 'lecturer'])
            ->when($role, fn ($query) => $query->where('skkm_role', $role))
            ->when(
                $role === 'mahasiswa' && $programStudiId,
                fn ($query) => $query->where('program_studi_id', $programStudiId)
            )
            ->when(
                $role === 'mahasiswa' && $semester !== null,
                fn ($query) => $query->where('semester', $semester)
            )
            ->when($search, function (Builder $query, string $searchTerm) {
                $safeSearchTerm = addcslashes($searchTerm, '\\%_');
                $query->where(function (Builder $innerQuery) use ($safeSearchTerm) {
                    $innerQuery
                        ->where('name', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('email', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('identifier', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('phone_number', 'like', '%' . $safeSearchTerm . '%');
                });
            });

        $this->applySorting($usersQuery, $sort);

        $users = $usersQuery
            ->paginate($perPage)
            ->withQueryString();

        return view('skkm.super-admin.users.index', [
            'users' => $users,
            'selectedSearch' => $search,
            'selectedRole' => $role,
            'selectedProgramStudi' => $programStudiId,
            'selectedSemester' => $semester,
            'selectedSort' => $sort,
            'sortOptions' => $sortOptions,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'semesterOptions' => range(1, 14),
            'programStudis' => ProgramStudi::query()->orderBy('jenjang')->orderBy('nama')->get(),
            'roleOptions' => $roleOptions,
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

        if (in_array($validated['skkm_role'], ['kaprodi', 'dosen_pa'], true)) {
            $validated['jenjang_studi'] = ProgramStudi::whereKey($validated['program_studi_id'])->value('jenjang');
        }

        if ($validated['skkm_role'] !== 'mahasiswa') {
            $validated['phone_number'] = null;
            $validated['semester'] = null;
            $validated['lecturer_id'] = null;
            if (! in_array($validated['skkm_role'], ['kaprodi', 'dosen_pa'], true)) {
                $validated['jenjang_studi'] = null;
            }
        }

        if (! in_array($validated['skkm_role'], ['mahasiswa', 'kaprodi', 'dosen_pa'], true)) {
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

        if (in_array($validated['skkm_role'], ['kaprodi', 'dosen_pa'], true)) {
            $validated['jenjang_studi'] = ProgramStudi::whereKey($validated['program_studi_id'])->value('jenjang');
        }

        if ($validated['skkm_role'] !== 'mahasiswa') {
            $validated['phone_number'] = null;
            $validated['semester'] = null;
            $validated['lecturer_id'] = null;
            if (! in_array($validated['skkm_role'], ['kaprodi', 'dosen_pa'], true)) {
                $validated['jenjang_studi'] = null;
            }
        }

        if (! in_array($validated['skkm_role'], ['mahasiswa', 'kaprodi', 'dosen_pa'], true)) {
            $validated['program_studi_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user, Request $request): RedirectResponse
    {
        if ((int) $user->id === (int) $request->user()->id) {
            return redirect()
                ->back()
                ->with('error', 'Akun super admin yang sedang aktif tidak bisa dihapus.');
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
     * @return array<string, string>
     */
    private function sortOptions(): array
    {
        return [
            'name_asc' => 'Nama (A-Z)',
            'name_desc' => 'Nama (Z-A)',
            'created_desc' => 'Terbaru Dibuat',
            'created_asc' => 'Terlama Dibuat',
            'role_asc' => 'Role (A-Z)',
            'role_desc' => 'Role (Z-A)',
            'program_studi_asc' => 'Program Studi (A-Z)',
            'program_studi_desc' => 'Program Studi (Z-A)',
        ];
    }

    private function applySorting(Builder $query, string $sort): void
    {
        $programStudiSortSubquery = ProgramStudi::query()
            ->select('nama')
            ->whereColumn('program_studis.id', 'users.program_studi_id')
            ->limit(1);

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'created_desc' => $query->orderBy('created_at', 'desc')->orderBy('name', 'asc'),
            'created_asc' => $query->orderBy('created_at', 'asc')->orderBy('name', 'asc'),
            'role_asc' => $query->orderBy('skkm_role', 'asc')->orderBy('name', 'asc'),
            'role_desc' => $query->orderBy('skkm_role', 'desc')->orderBy('name', 'asc'),
            'program_studi_asc' => $query
                ->orderBy($programStudiSortSubquery, 'asc')
                ->orderBy('name', 'asc'),
            'program_studi_desc' => $query
                ->orderBy($programStudiSortSubquery, 'desc')
                ->orderBy('name', 'asc'),
            default => $query->orderBy('name', 'asc'),
        };
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
                Rule::requiredIf(fn () => in_array($request->input('skkm_role'), ['mahasiswa', 'kaprodi', 'dosen_pa'], true)),
                'exists:program_studis,id',
            ],
            'jenjang_studi' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('skkm_role') === 'mahasiswa'),
                Rule::in(['S1', 'D4', 'D3']),
            ],
            'phone_number' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('skkm_role') === 'mahasiswa'),
                'string',
                'max:25',
                'regex:/^\+?[0-9][0-9\s().-]{7,24}$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! User::normalizeWhatsappPhoneNumber((string) $value)) {
                        $fail('Nomor HP / WhatsApp harus berisi 10 sampai 15 digit yang valid.');
                    }
                },
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
