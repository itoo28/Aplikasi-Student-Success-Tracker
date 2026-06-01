<?php

namespace App\Http\Controllers\Skkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramStudiController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $search = $search !== '' ? $search : null;

        $programStudis = ProgramStudi::query()
            ->with('fakultas')
            ->when($search, function (Builder $query, string $searchTerm) {
                $safeSearchTerm = addcslashes($searchTerm, '\\%_');
                $query->where(function (Builder $innerQuery) use ($safeSearchTerm) {
                    $innerQuery
                        ->where('nama', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('kode', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('jenjang', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhereHas('fakultas', function (Builder $fakultasQuery) use ($safeSearchTerm) {
                            $fakultasQuery
                                ->where('nama', 'like', '%' . $safeSearchTerm . '%')
                                ->orWhere('kode', 'like', '%' . $safeSearchTerm . '%');
                        });
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('skkm.super-admin.program-studi.index', compact('programStudis', 'search'));
    }

    public function create(): View
    {
        return view('skkm.super-admin.program-studi.form', [
            'programStudi' => new ProgramStudi(),
            'fakultas' => Fakultas::query()->where('is_active', true)->orderBy('nama')->get(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        ProgramStudi::create($validated);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $program_studi): View
    {
        return view('skkm.super-admin.program-studi.form', [
            'programStudi' => $program_studi,
            'fakultas' => Fakultas::query()->where('is_active', true)->orderBy('nama')->get(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, ProgramStudi $program_studi): RedirectResponse
    {
        $validated = $this->validateData($request, $program_studi);
        $program_studi->update($validated);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $program_studi): RedirectResponse
    {
        if ($program_studi->users()->exists()) {
            return redirect()->back()->withErrors(['delete_program_studi' => 'Program studi tidak bisa dihapus karena masih dipakai user.']);
        }

        $program_studi->delete();

        return redirect()->route('admin.program-studi.index')->with('success', 'Program studi berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?ProgramStudi $programStudi = null): array
    {
        return $request->validate([
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'kode' => ['required', 'string', 'max:20', Rule::unique('program_studis', 'kode')->ignore($programStudi?->id)],
            'nama' => ['required', 'string', 'max:100'],
            'jenjang' => ['required', Rule::in(['S1', 'D4', 'D3'])],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
